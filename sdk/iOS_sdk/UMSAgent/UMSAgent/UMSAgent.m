/**
 * Cobub Razor
 *
 * An open source analytics iphone sdk for mobile applications
 *
 * @package		Cobub Razor
 * @author		WBTECH Dev Team
 * @copyright	Copyright (c) 2011 - 2012, NanJing Western Bridge Co.,Ltd.
 * @license		http://www.cobub.com/products/cobub-razor/license
 * @link		http://www.cobub.com/products/cobub-razor/
 * @since		Version 0.1
 * @filesource
 */

#import "UMSAgent.h"
#import "CheckUpdateReturn.h"
#import "CheckUpdateDao.h"
#import "PostClientDataDao.h"
#import "PostTagDao.h"
#import "postEventDao.h"
#import "Event.h"
#import "Global.h"
#import "GetOnlineConfigDao.h"
#import "asl.h"
#import <SystemConfiguration/SystemConfiguration.h>
#import <arpa/inet.h> // For AF_INET, etc.
#import <ifaddrs.h> // For getifaddrs()
#import <net/if.h> // For IFF_LOOPBACK
#import <CoreTelephony/CTTelephonyNetworkInfo.h>
#import <CoreTelephony/CTCarrier.h>
#import <CoreTelephony/CTCall.h>
#import <CoreTelephony/CTCallCenter.h>
#import "OpenUDID.h"
#import <CommonCrypto/CommonDigest.h> // Need to import for CC_MD5 access
#import "ActivityLog.h"
#import "ErrorLog.h"
#import "OpenUDID.h"
#import <sys/utsname.h>
#import "ClientData.h"
#import "Tag.h"
#import <AdSupport/AdSupport.h>
#import "SFHFKeychainUtils.h"

@interface UMSAgent ()
{
    NSString *appKey;
    ReportPolicy policy;
    BOOL isLogEnabled;
    BOOL isCrashReportEnabled;
    NSDate *startDate;
    NSDate *sessionStopDate;
    NSString *updateOnliWifi;
    NSString *sessionmillis;
    BOOL *isOnlineConfig;

    CheckUpdateReturn *updateRet;
    NSMutableArray *eventArrary;
    NSString *sessionId;
    NSString *pageName;
}

@property (nonatomic) ReportPolicy policy;
@property (nonatomic) BOOL isLogEnabled;
@property (nonatomic) BOOL isCrashReportEnabled;
@property (nonatomic,strong) NSString *appKey;
@property (nonatomic,strong) NSString *updateOnlyWifi;
@property (nonatomic,strong) NSString *sessionmillis;
@property (nonatomic) BOOL isOnLineConfig;
@property (nonatomic) CheckUpdateReturn *updateRet;
@property (nonatomic,strong) NSDate *startDate;
@property (nonatomic,strong) NSString *sessionId;
@property (nonatomic,strong) NSString *pageName;
@property (nonatomic,strong) NSDate *sessionStopDate;

@end

@implementation UMSAgent
@synthesize policy;
@synthesize isLogEnabled;
@synthesize isCrashReportEnabled;
@synthesize updateOnlyWifi,sessionmillis,isOnLineConfig;
@synthesize updateRet;
@synthesize appKey;
@synthesize startDate;
@synthesize sessionId;
@synthesize pageName;
@synthesize sessionStopDate;

+(UMSAgent*)getInstance
{
    static UMSAgent *instance = nil;
    if(instance == nil)
    {
        instance = [[[self class] alloc] init];
        instance.isLogEnabled = NO;
        instance.isCrashReportEnabled = YES;
        instance.policy = 1;
        instance.sessionmillis = @"30";
        instance.updateOnlyWifi =  @"1";        
    }
    return instance;
}

+(void)startWithAppKey:(NSString*)appKey ServerURL:(NSString *)serverURL
{
    [[UMSAgent getInstance] initWithAppKey:appKey reportPolicy:BATCH serverURL:serverURL];
    
}

+(void)startWithAppKey:(NSString*)appKey ReportPolicy:(ReportPolicy)policy ServerURL:(NSString*)serverURL
{
    [[UMSAgent getInstance] initWithAppKey:appKey reportPolicy:policy serverURL:serverURL];
}

+ (void)setIsLogEnabled:(BOOL)isLogEnabled
{
    [UMSAgent getInstance].isLogEnabled = isLogEnabled;
}


-(void)initWithAppKey:(NSString*)applicationKey reportPolicy:(ReportPolicy)m_policy serverURL:(NSString*)m_serverURL
{    
    self.appKey = applicationKey;
    self.policy = m_policy;
    [Global setBaseURL:m_serverURL];
    NSNotificationCenter *notifCenter = [NSNotificationCenter defaultCenter];
    [notifCenter addObserver:self
                    selector:@selector(resignActive:)
                        name:UIApplicationWillResignActiveNotification
                      object:nil];
    [notifCenter addObserver:self
                    selector:@selector(becomeActive:)
                        name:UIApplicationWillEnterForegroundNotification
                      object:nil];
    [[UIApplication sharedApplication]registerForRemoteNotificationTypes: UIRemoteNotificationTypeBadge | 
     UIRemoteNotificationTypeAlert |
     UIRemoteNotificationTypeSound];
    
    
    self.startDate = [[NSDate date] copy];
    NSString *currentTime = [[NSString alloc] initWithFormat:@"%f",[[NSDate date] timeIntervalSince1970]];
    NSString *sessionIdentifier = [[NSString alloc] initWithFormat:@"%@%@", currentTime, [UMSAgent getUMSUDID]
                                   ];
    self.sessionId = [self md5:sessionIdentifier];
    if(isLogEnabled)
    {
        NSLog(@"Get Session ID = %@",sessionId);
    }
    NSSetUncaughtExceptionHandler(&uncaughtExceptionHandler);
    [self performSelectorInBackground:@selector(archiveClientData) withObject:nil];
}



+(void)startTracPage:(NSString*)page_name
{
    [[UMSAgent getInstance] performSelectorInBackground:@selector(recordStartTime:) withObject:page_name];
}

-(void)recordStartTime:(NSString*) page_name
{
    @autoreleasepool {
        self.pageName = [[NSString alloc] initWithString:page_name];
        NSDate *pageStartDate = [[NSDate date] copy];
        [[NSUserDefaults standardUserDefaults] setObject:pageStartDate forKey:page_name];
        [[NSUserDefaults standardUserDefaults] synchronize];
    }
}

+(void)endTracPage:(NSString*)page_name
{
    if([UMSAgent getInstance].policy == REALTIME)
    {
//        if([UMSAgent getInstance].isLogEnabled)
//        {
//            NSLog(@"Commit using Time of page %@",page_name);
//        }
        [[UMSAgent getInstance] performSelectorInBackground:@selector(commitUsingTime:) withObject:page_name];
    }
    else
    {
//        if([UMSAgent getInstance].isLogEnabled)
//        {
//            NSLog(@"Save Activity using time to cache of %@",page_name);
//        }
        [[UMSAgent getInstance] performSelectorInBackground:@selector(saveActivityUsingTime:) withObject:page_name];
    }

}

- (void)resignActive:(NSNotification *)notification 
{
       if(self.pageName!=nil)
       {
           [UMSAgent endTracPage:self.pageName];
       }
    
        sessionStopDate = [NSDate date];
        if(isLogEnabled)
        {
            NSLog(@"Resign Active: click home button or lose focus. End Trace Page and save session stop date.");
        }
    //Since We use viewWillAppear and viewWillDisappear to record, So just remove from here
//    NSString *page_name = [[NSBundle mainBundle] bundleIdentifier];
//    if(policy == REALTIME)
//    {
//        if(isLogEnabled)
//        {
//            NSLog(@"Commit using Time");
//        }
//        [self performSelectorInBackground:@selector(commitUsingTime:) withObject:page_name];
//    }
//    else
//    {
//        if(isLogEnabled)
//        {
//            NSLog(@"Save Activity using time to cache");
//        }
//        [self performSelectorInBackground:@selector(saveActivityUsingTime:) withObject:page_name];
//    }
}

- (void)becomeActive:(NSNotification *)notification 
{
    if(isLogEnabled)
    {
        NSLog(@"Application become active");
    }
    if(self.pageName!=nil)
    {
        [UMSAgent startTracPage:self.pageName];
        NSString *page_name = [[NSBundle mainBundle] bundleIdentifier];
        NSDate *pageStartDate = [[NSDate date] copy];
        [[NSUserDefaults standardUserDefaults] setObject:pageStartDate forKey:page_name];
        [[NSUserDefaults standardUserDefaults] synchronize];
    }
    NSString *currentTime = [[NSString alloc] initWithFormat:@"%f",[[NSDate date] timeIntervalSince1970]];
    if(sessionStopDate!=nil)
    {
        NSTimeInterval sessionStopInterval = -[sessionStopDate timeIntervalSinceNow];
        if(sessionStopInterval + 0.0000001 > 30)
        {
            self.sessionId = [self md5:currentTime];
            if(isLogEnabled)
            {
                NSLog(@"Stop session more than 30 seconds, so consider as new session id.");
            }
        }
        else
        {
            if(isLogEnabled)
            {
                NSLog(@"Stop session less than 30 seconds, so consider as old session.");
            }
        }
    }
    else
    {
        self.sessionId = [self md5:currentTime];
    }
    if(isLogEnabled)
    {
        NSLog(@"Current session ID = %@",sessionId);
    }
    
    [self performSelectorInBackground:@selector(archiveClientData) withObject:nil];
    
    if(isLogEnabled)
    {
        NSLog(@"Application Resign Active");
    }
    
}

-(void)commitUsingTime:(NSString*)currentPageName
{
    @autoreleasepool 
    {
        NSString *session_mills = self.sessionId;
        NSString *end_mils = [self getCurrentTime];
        NSDate *pageStartDate = [[NSUserDefaults standardUserDefaults] objectForKey:currentPageName];
        if(pageStartDate!=nil)
        {
            NSString *start_mils = [self getDateStr:pageStartDate];
            NSTimeInterval duration = (-[pageStartDate timeIntervalSinceNow])*1000;
            NSString *durationStr = [[NSString alloc] initWithFormat:@"%f",duration];
            NSString *activities = currentPageName;
            NSString *appVersion = [self getVersion];
            [PostClientDataDao postUsingTime:appKey sessionMills:session_mills startMils:start_mils endMils:end_mils duration:durationStr activity:activities version:appVersion];
            [[NSUserDefaults standardUserDefaults] removeObjectForKey:currentPageName];
            [[NSUserDefaults standardUserDefaults] synchronize];
        }
        else
        {
           if(isLogEnabled)
           {
                NSLog(@"Page Start time not found. in commitUsingTime pagename = %@",currentPageName);
           }
        }
    }
}

- (void)saveErrorLog:(NSString*)stackTrace
{
    @autoreleasepool {
        if(isLogEnabled)
        {
            NSLog(@"save error log");
        }
        ErrorLog *errorLog = [[ErrorLog alloc] init];
        errorLog.stackTrace = stackTrace;
        errorLog.appkey = self.appKey;
        errorLog.version = [self getVersion];
        errorLog.time = [self getCurrentTime];
        errorLog.activity = [[NSBundle mainBundle] bundleIdentifier];
        errorLog.osVersion = [[UIDevice currentDevice] systemVersion];
        errorLog.deviceID = [self machineName];
        NSLog(@"Error Log");
        NSData *errorLogData = [[NSUserDefaults standardUserDefaults] objectForKey:@"errorLog"] ;
        NSMutableArray * errorLogArray = [[NSMutableArray alloc] init ];
        if (errorLogData!=nil) 
        {
            errorLogArray = [NSKeyedUnarchiver unarchiveObjectWithData:errorLogData];
        }
        else {
            errorLogArray = [[NSMutableArray alloc] init ];
        }
        [errorLogArray addObject:errorLog];
        if(isLogEnabled)
        {
            NSLog(@"Error Log array size = %d",[errorLogArray count]);
        }
        NSData *newErrorData = [NSKeyedArchiver archivedDataWithRootObject:errorLogArray];
        [[NSUserDefaults standardUserDefaults] setObject:newErrorData forKey:@"errorLog"];
        [[NSUserDefaults standardUserDefaults] synchronize];

    }
}

- (void)saveActivityUsingTime:(NSString*)currentPageName
{
    @autoreleasepool 
    {
        ActivityLog *acLog = [[ActivityLog alloc] init];
        acLog.sessionMils = self.sessionId;
        NSDate *pageStartDate = [[NSUserDefaults standardUserDefaults] objectForKey:currentPageName];
        if(pageStartDate!=nil)
        {
            NSString *start_mils = [self getDateStr:pageStartDate];
            acLog.startMils = start_mils;
            [[NSUserDefaults standardUserDefaults] removeObjectForKey:currentPageName];
            [[NSUserDefaults standardUserDefaults] synchronize];
        }
        else
        {
            if(isLogEnabled)
            {
                 NSLog(@"Page Start time not found. in saveActivityUsingTime pagename = %@",currentPageName);
            }   
            return;
        }
        acLog.endMils = [self getCurrentTime];
        NSTimeInterval duration = (-[pageStartDate timeIntervalSinceNow])*1000;
        acLog.duration = [[NSString alloc] initWithFormat:@"%f",duration];
        acLog.activity = currentPageName;
        acLog.version = [self getVersion];
        if(acLog)
        {
            NSLog(@"acLog sessionMils = %@",acLog.sessionMils);
        }
        NSData *activityLogData = [[NSUserDefaults standardUserDefaults] objectForKey:@"activityLog"] ;
        NSMutableArray * activityLogArray = [[NSMutableArray alloc] init ];
        if (activityLogData!=nil) 
        {
            activityLogArray = [NSKeyedUnarchiver unarchiveObjectWithData:activityLogData];
        }
        else {
            activityLogArray = [[NSMutableArray alloc] init ];
        }
        [activityLogArray addObject:acLog];
        if(isLogEnabled)
        {
            NSLog(@"Activity Log array size = %d",[activityLogArray count]);
        }
        NSData *newActivityData = [NSKeyedArchiver archivedDataWithRootObject:activityLogArray];
        [[NSUserDefaults standardUserDefaults] setObject:newActivityData forKey:@"activityLog"];
        [[NSUserDefaults standardUserDefaults] synchronize];
    }
}

-(NSString *)md5:(NSString *)str { 
    const char *cStr = [str UTF8String]; 
    unsigned char result[32]; 
    CC_MD5( cStr, strlen(cStr), result ); 
    return [NSString stringWithFormat: 
            @"%02X%02X%02X%02X%02X%02X%02X%02X%02X%02X%02X%02X%02X%02X%02X%02X",
            result[0], result[1], result[2], result[3], 
            result[4], result[5], result[6], result[7], 
            result[8], result[9], result[10], result[11], 
            result[12], result[13], result[14], result[15] 
            ]; 
}

+(void)checkUpdate
{
    if ([UMSAgent getInstance].updateOnlyWifi)
    {
        [[UMSAgent getInstance] getApplicationUpdate];
    }
}

-(void) getApplicationUpdate
{
    CheckUpdateReturn *retWrapper;
    if(isLogEnabled)
    {
        NSLog(@"Begin get application update");
    }
    retWrapper = [CheckUpdateDao checkUpdate:appKey version:@"1.0"];
    if (retWrapper.flag>0)
    {
        updateRet = retWrapper;
        NSString *version = [[NSString alloc] initWithFormat:@"New Update %@",retWrapper.version];
        UIAlertView *alert = [[UIAlertView alloc] initWithTitle: version
                                                        message: retWrapper.description
                                                       delegate: self
                                              cancelButtonTitle:@"Cancel"
                                              otherButtonTitles:@"Confirm", nil];
        [alert show];
    }
    else 
    {
        if(isLogEnabled)
        {
            NSLog(@"Update Return: Flag = %d, Msg = %@",retWrapper.flag,retWrapper.msg);
        }
    }
}

-(void) postDataInBackGround
{
    CheckUpdateReturn *returnData = [CheckUpdateDao checkUpdate:appKey version:@"1.0"];
    [self performSelectorOnMainThread:@selector(end_postdataThread:) withObject:returnData waitUntilDone:NO];
}


-(void) end_postdataThread:(id)ret
{
    CheckUpdateReturn *retObj  =ret;
    if (retObj.flag>0) {
        
    }
}

+(void)postEvent:(NSString *)event_id
{
    Event *event =[[Event alloc] init];
    event.event_id = event_id;
    event.activity = [[NSBundle mainBundle] bundleIdentifier];
    event.label = @"";
    event.time = [[UMSAgent getInstance] getCurrentTime];
    event.version = [[UMSAgent getInstance] getVersion];
    event.acc = 1;
    [[UMSAgent getInstance] archiveEvent:event];
}

+(void)postEvent:(NSString *)event_id label:(NSString *)label
{
    Event *event = [[Event alloc] init];
    event.event_id = event_id;
    event.time = [[UMSAgent getInstance] getCurrentTime];
    event.acc = 1;
    event.version = [[UMSAgent getInstance] getVersion];
    event.activity = [[NSBundle mainBundle] bundleIdentifier];
    event.label = label;
    [[UMSAgent getInstance] archiveEvent:event];
    
}

+(void)postEvent:(NSString *)event_id acc:(NSInteger)acc
{
    Event *event = [[Event alloc] init];
    event.event_id = event_id;
    event.time = [[UMSAgent getInstance] getCurrentTime];
    event.acc = acc;
    event.version = [[UMSAgent getInstance] getVersion];
    event.activity =[[NSBundle mainBundle] bundleIdentifier];
    event.label = @"";
    [[UMSAgent getInstance] archiveEvent:event];
    
}

+(void)postEvent:(NSString *)event_id label:(NSString *)label acc:(NSInteger)acc
{
    Event *event = [[Event alloc] init];
    event.event_id = event_id;
    event.time = [[UMSAgent getInstance] getCurrentTime];
    event.acc = acc;
    event.activity = [[NSBundle mainBundle] bundleIdentifier];
    event.version = [[UMSAgent getInstance] getVersion];
    event.label = label;
    [[UMSAgent getInstance] archiveEvent:event];
}

+(void)postTag:(NSString *)tag
{
    Tag *tags = [[Tag alloc] init];
    tags.tags = tag;
    tags.productkey = [[UMSAgent getInstance] appKey];
    tags.deviceid = [UMS_OpenUDID value];
    
    
    [[UMSAgent getInstance] archiveTag:tags];
}

+(void)bindUserIdentifier:(NSString *)userid
{
    
    [[NSUserDefaults standardUserDefaults] setObject:userid forKey:@"userid"];
    [[NSUserDefaults standardUserDefaults] synchronize];

}


-(void) processEvent:(Event *)event
{
    [self performSelectorInBackground:@selector(postEventInBackGround:) withObject:event];
}

-(void) processTag:(Tag *)tag
{
    [self performSelectorInBackground:@selector(postTagInBackGround:) withObject:tag];
}

-(void) processArchivedLogs
{
    @autoreleasepool {
        NSMutableArray *eventArray = [self getArchiveEvent];
        NSMutableArray *activityLogArray = [self getArchiveActivityLog];
        NSMutableArray *errorLogArray = [self getArchiveErrorLog];
        NSMutableArray *clientDataArray = [self getArchiveClientData];
        NSMutableArray *tagArray = [self getArchiveTag];
        if([eventArray count]>0 || [activityLogArray count]>0 || [errorLogArray count]>0 || [clientDataArray count]>0 || [tagArray count]>0)
        {
            NSMutableDictionary *requestDic = [[NSMutableDictionary alloc] init];
            [requestDic setObject:appKey forKey:@"appkey"];
            if([eventArray count]>0)
            {
                [requestDic setObject:eventArray forKey:@"eventInfo"];
            }
            
            if([tagArray count]>0)
            {
                [requestDic setObject:tagArray forKey:@"tagInfo"];
            }
            
            if([activityLogArray count] >0)
            {
                [requestDic setObject:activityLogArray forKey:@"activityInfo"];
            }
            
            if([errorLogArray count]>0)
            {
                [requestDic setObject:errorLogArray forKey:@"errorInfo"];
            }
            
            if([clientDataArray count]>0)
            {
                [requestDic setObject:clientDataArray forKey:@"clientData"];
            }
            
            if(isLogEnabled)
            {
                NSLog(@"Post Archive Logs");
            }
            CommonReturn *ret = [PostClientDataDao postArchiveLogs:requestDic];
            if(ret.flag>0)
            {
                if (isLogEnabled) 
                {
                    NSLog(@"Arcived log upload success, so remove archived logs in cache");
                }
                [[NSUserDefaults standardUserDefaults] removeObjectForKey:@"eventArray"];
                [[NSUserDefaults standardUserDefaults] removeObjectForKey:@"tagArray"];
                [[NSUserDefaults standardUserDefaults] removeObjectForKey:@"activityLog"];
                [[NSUserDefaults standardUserDefaults] removeObjectForKey:@"errorLog"];
                [[NSUserDefaults standardUserDefaults] removeObjectForKey:@"clientDataArray"];
            }
        }
    }
}

-(NSMutableArray *)getArchiveEvent
{
    NSData *oldData = [[NSUserDefaults standardUserDefaults] objectForKey:@"eventArray"] ;
    NSMutableArray * array = nil;
    if(isLogEnabled)
    {
        NSLog(@"old  data num = %d",[array count]);
    }
    
    if (oldData!=nil) 
    {
        array = [NSKeyedUnarchiver unarchiveObjectWithData:oldData];
    }
    NSMutableArray *eventArray = [[NSMutableArray alloc] init];
    if ([array count]>0)
    {
        for(Event *mEvent in array)
        {
            NSMutableDictionary *requestDictionary = [[NSMutableDictionary alloc] init];
            [requestDictionary setObject:mEvent.event_id forKey:@"event_identifier"];
            [requestDictionary setObject:mEvent.time forKey:@"time"];
            [requestDictionary setObject:mEvent.activity forKey:@"activity"];
            [requestDictionary setObject:mEvent.label forKey:@"label"];
            [requestDictionary setObject:[NSNumber numberWithInt:mEvent.acc] forKey:@"acc"];
            [requestDictionary setObject:appKey forKey:@"appkey"];
            [requestDictionary setObject:mEvent.version forKey:@"version"];
            [eventArray addObject:requestDictionary];
        }
    }
    return eventArray;
}

-(NSMutableArray *)getArchiveTag
{
    NSData *oldData = [[NSUserDefaults standardUserDefaults] objectForKey:@"tagArray"] ;
    NSMutableArray * array = nil;
    if(isLogEnabled)
    {
        NSLog(@"old  data num = %d",[array count]);
    }
    
    if (oldData!=nil)
    {
        array = [NSKeyedUnarchiver unarchiveObjectWithData:oldData];
    }
    NSMutableArray *tagArray = [[NSMutableArray alloc] init];
    if ([array count]>0)
    {
        for(Tag *mTag in array)
        {
            NSMutableDictionary *requestDictionary = [[NSMutableDictionary alloc] init];
            [requestDictionary setObject:mTag.deviceid forKey:@"deviceid"];
            [requestDictionary setObject:mTag.tags forKey:@"tags"];
            [requestDictionary setObject:mTag.productkey forKey:@"productkey"];
            [tagArray addObject:requestDictionary];
        }
    }
    return tagArray;
}


-(NSMutableArray *)getArchiveActivityLog
{
    NSData *oldData = [[NSUserDefaults standardUserDefaults] objectForKey:@"activityLog"] ;
    NSMutableArray * array = nil;
    if (oldData!=nil) 
    {
        array = [NSKeyedUnarchiver unarchiveObjectWithData:oldData];
        if(isLogEnabled)
        {
            NSLog(@"Have activity data num = %d",[array count]);
        }
    }
    NSMutableArray *activityLogArray = [[NSMutableArray alloc] init];
    if ([array count]>0)
    {
        for(ActivityLog *mLog in array)
        {
            NSMutableDictionary *requestDictionary = [[NSMutableDictionary alloc] init];
            [requestDictionary setObject:mLog.sessionMils forKey:@"session_id"];
            [requestDictionary setObject:mLog.startMils forKey:@"start_millis"];
            [requestDictionary setObject:mLog.endMils forKey:@"end_millis"];
            [requestDictionary setObject:mLog.duration forKey:@"duration"];
            [requestDictionary setObject:mLog.activity forKey:@"activities"];
            [requestDictionary setObject:appKey forKey:@"appkey"];   
            [requestDictionary setObject:mLog.version forKey:@"version"]; 
            [activityLogArray addObject:requestDictionary];
        }
    }
    return activityLogArray;
}

-(NSMutableArray *)getArchiveErrorLog
{
    NSData *oldData = [[NSUserDefaults standardUserDefaults] objectForKey:@"errorLog"] ;
    NSMutableArray * array = nil;
    if (oldData!=nil) 
    {
        array = [NSKeyedUnarchiver unarchiveObjectWithData:oldData];
        if(isLogEnabled)
        {
            NSLog(@"Have error data num = %d",[array count]);
        }
    }
    NSMutableArray *errorLogArray = [[NSMutableArray alloc] init];
    if ([array count]>0)
    {
        for(ErrorLog *errorLog in array)
        {
            NSMutableDictionary *requestDictionary = [[NSMutableDictionary alloc] init];
            [requestDictionary setObject:errorLog.time forKey:@"time"];
            [requestDictionary setObject:errorLog.stackTrace forKey:@"stacktrace"];
            [requestDictionary setObject:errorLog.version forKey:@"version"];
            [requestDictionary setObject:errorLog.osVersion forKey:@"os_version"];
            [requestDictionary setObject:errorLog.deviceID forKey:@"deviceid"];
            [requestDictionary setObject:errorLog.appkey forKey:@"appkey"];
            [requestDictionary setObject:errorLog.activity forKey:@"activity"];
            [errorLogArray addObject:requestDictionary];
        }
    }
    return errorLogArray;
}


-(void)postEventInBackGround:(Event *)event
{            
    @autoreleasepool {
        CommonReturn *ret ;
        ret = [postEventDao postEvent:self.appKey event:event];
        if (ret.flag<0) 
        {
            NSData *oldData = [[NSUserDefaults standardUserDefaults] objectForKey:@"eventArray"] ;
            NSMutableArray * array = [[NSMutableArray alloc] init];
            
            if (oldData!=nil) 
            {
                array = [NSKeyedUnarchiver unarchiveObjectWithData:oldData];
            }
            [array addObject:event];
            NSData *newData = [NSKeyedArchiver archivedDataWithRootObject:array];
            [[NSUserDefaults standardUserDefaults] setObject:newData forKey:@"eventArray"];
            [[NSUserDefaults standardUserDefaults] synchronize];
        }
    }
}

-(void)postTagInBackGround:(Tag *)tag
{
    @autoreleasepool {
        CommonReturn *ret ;
        ret = [PostTagDao postTag:self.appKey tag:tag];
        if (ret.flag<0)
        {
            NSData *oldData = [[NSUserDefaults standardUserDefaults] objectForKey:@"tagArray"] ;
            NSMutableArray * array = [[NSMutableArray alloc] init];
            
            if (oldData!=nil)
            {
                array = [NSKeyedUnarchiver unarchiveObjectWithData:oldData];
            }
            [array addObject:tag];
            NSData *newData = [NSKeyedArchiver archivedDataWithRootObject:array];
            [[NSUserDefaults standardUserDefaults] setObject:newData forKey:@"tagArray"];
            [[NSUserDefaults standardUserDefaults] synchronize];
        }
    }
}



-(void)postOldEventDataInBackGround:(NSMutableArray *)array
{        
    @autoreleasepool {   
    for (int i =0; i<[array count]; i++) 
    {
        Event *event = [array objectAtIndex:i];
        
        CommonReturn *ret ;
        ret = [postEventDao postEvent:appKey event:event];
        if (ret.flag>0) 
        {
            [array removeObjectAtIndex:i];

        }
                
        NSData *newData = [NSKeyedArchiver archivedDataWithRootObject:array];
        
        [[NSUserDefaults standardUserDefaults] setObject:newData forKey:@"eventArray"];
        [[NSUserDefaults standardUserDefaults] synchronize];
    }
    }
}

-(void)archiveClientData
{
    ClientData *clientData = [self getDeviceInfo];
    NSMutableArray *mClientDataArray;
    if (self.policy == BATCH) {
        NSData *oldData = [[NSUserDefaults standardUserDefaults] objectForKey:@"clientDataArray"] ;
        if (oldData!=nil)
        {
            mClientDataArray = [NSKeyedUnarchiver unarchiveObjectWithData:oldData];
        }
        else
        {
            mClientDataArray = [[NSMutableArray alloc] init];
        }
        if(isLogEnabled)
        {
            NSLog(@"archive client data because of BATCH mode");
        }
        [mClientDataArray addObject:clientData];
        if(isLogEnabled)
        {
            NSLog(@"Archived client data = %d",[mClientDataArray count]);
        }
        NSData *newData = [NSKeyedArchiver archivedDataWithRootObject:mClientDataArray];
        [[NSUserDefaults standardUserDefaults] setObject:newData forKey:@"clientDataArray"];
        [[NSUserDefaults standardUserDefaults] synchronize];
    }
    else
    {
        
        [self processClientData:clientData];
    }
    
    //Process archived logs after post ClientData
    [self performSelector:@selector(processArchivedLogs)];

}

-(void)processClientData:(ClientData *)clientData
{
    [self performSelector:@selector(postClientDataInBackground:) withObject:clientData];
}

-(NSMutableArray *)getArchiveClientData
{
    NSData *oldData = [[NSUserDefaults standardUserDefaults] objectForKey:@"clientDataArray"] ;
    NSMutableArray * array = nil;
    if (oldData!=nil)
    {
        array = [NSKeyedUnarchiver unarchiveObjectWithData:oldData];
        if(isLogEnabled)
        {
            NSLog(@"Have error data num = %d",[array count]);
        }
    }
    NSMutableArray *clientDataArray = [[NSMutableArray alloc] init];
    if ([array count]>0)
    {
        for(ClientData *clientData in array)
        {
            NSMutableDictionary *requestDictionary = [[NSMutableDictionary alloc] init];
            [requestDictionary setObject:clientData.platform forKey:@"platform"];
            [requestDictionary setObject:clientData.os_version forKey:@"os_version"];
            [requestDictionary setObject:clientData.language forKey:@"language"];
            [requestDictionary setObject:clientData.resolution forKey:@"resolution"];
            [requestDictionary setObject:clientData.deviceid forKey:@"deviceid"];
            if (clientData.userid!=nil) {
                [requestDictionary setObject:clientData.userid forKey:@"userid"];
            }
            else
            {
                [requestDictionary setObject:@"" forKey:@"userid"];
            }
            
            if(clientData.mccmnc!=nil)
            {
                [requestDictionary setObject:clientData.mccmnc forKey:@"mccmnc"];
            }
            else
            {
                [requestDictionary setObject:@"" forKey:@"mccmnc"];

            }
            [requestDictionary setObject:clientData.version forKey:@"version"];
            [requestDictionary setObject:clientData.network forKey:@"network"];
            [requestDictionary setObject:clientData.devicename forKey:@"devicename"];
            [requestDictionary setObject:clientData.modulename forKey:@"modulename"];
            [requestDictionary setObject:clientData.time forKey:@"time"];
            [requestDictionary setObject:appKey forKey:@"appkey"];
            [requestDictionary setObject:clientData.isjailbroken forKey:@"isjailbroken"];
            [clientDataArray addObject:requestDictionary];
        }
    }
    return clientDataArray;
}



-(void)archiveEvent:(Event *)event
{
    NSMutableArray *mEventArray;
    if (self.policy == BATCH) {
        NSData *oldData = [[NSUserDefaults standardUserDefaults] objectForKey:@"eventArray"] ;
        if (oldData!=nil) 
        {
            mEventArray = [NSKeyedUnarchiver unarchiveObjectWithData:oldData];
        }
        else 
        {
            mEventArray = [[NSMutableArray alloc] init];
        }
        if(isLogEnabled)
        {
            NSLog(@"archive event because of BATCH mode");
        }
        [mEventArray addObject:event];
        if(isLogEnabled)
        {
            NSLog(@"Archived event count = %d",[mEventArray count]);
        }
        NSData *newData = [NSKeyedArchiver archivedDataWithRootObject:mEventArray];
        [[NSUserDefaults standardUserDefaults] setObject:newData forKey:@"eventArray"];
        [[NSUserDefaults standardUserDefaults] synchronize];
    }
    else
    {
        [self processEvent:event];
    }
}

-(void)archiveTag:(Tag *)tag
{
    NSMutableArray *mTagArray;
    if (self.policy == BATCH) {
        NSData *oldData = [[NSUserDefaults standardUserDefaults] objectForKey:@"tagArray"] ;
        if (oldData!=nil)
        {
            mTagArray = [NSKeyedUnarchiver unarchiveObjectWithData:oldData];
        }
        else
        {
            mTagArray = [[NSMutableArray alloc] init];
        }
        if(isLogEnabled)
        {
            NSLog(@"archive tag because of BATCH mode");
        }
        [mTagArray addObject:tag];
        if(isLogEnabled)
        {
            NSLog(@"Archived tag count = %d",[mTagArray count]);
        }
        NSData *newData = [NSKeyedArchiver archivedDataWithRootObject:mTagArray];
        [[NSUserDefaults standardUserDefaults] setObject:newData forKey:@"tagArray"];
        [[NSUserDefaults standardUserDefaults] synchronize];
    }
    else
    {
        [self processTag:tag];
    }
}

-(NSString *) getVersion
{
    NSString *appVersion = [[[NSBundle mainBundle] infoDictionary] objectForKey:@"CFBundleShortVersionString"];
    return appVersion;
}

-(ClientData *)getDeviceInfo
{
    ClientData  *info = [[ClientData alloc] init];
    info.platform = [[UIDevice currentDevice] systemName];
    info.devicename = [self machineName];
    info.modulename = [[UIDevice currentDevice] model];
    info.os_version = [[UIDevice currentDevice] systemVersion];
    info.time = [self getCurrentTime];
    if([UMSAgent isJailbroken])
    {
        info.isjailbroken = @"1";
    }
    else {
        info.isjailbroken = @"0";
    }
    
    CGRect rect = [[UIScreen mainScreen] bounds];
    CGFloat scale = [[UIScreen mainScreen] scale];
    info.resolution = [[NSString alloc] initWithFormat:@"%.fx%.f",rect.size.width*scale,rect.size.height*scale];
    //Using open UDID 
    info.deviceid = [UMSAgent getUMSUDID];
    NSUserDefaults *defaults = [NSUserDefaults standardUserDefaults]; 
    NSArray *languages = [defaults objectForKey:@"AppleLanguages"]; 
    info.language = [languages objectAtIndex:0];
    
    NSString *userid = [[NSUserDefaults standardUserDefaults] objectForKey:@"userid"];
    if (userid==nil) {
        userid = @"";
    }
        
    info.userid = userid;    
    
    CTTelephonyNetworkInfo*netInfo =[[CTTelephonyNetworkInfo alloc] init];
    CTCarrier*carrier =[netInfo subscriberCellularProvider];
    NSString*mcc =[carrier mobileCountryCode];
    NSString*mnc =[carrier mobileNetworkCode];
    info.mccmnc = [mcc stringByAppendingString:mnc];
    
    info.version = [self getVersion];
    BOOL isWifi = [self isWiFiAvailable];
    if(isWifi)
    {
        info.network = @"WIFI";
    }
    else
    {
        info.network = @"2G/3G";
    }
    return info;
}

-(NSString *)getCurrentTime
{
    NSDateFormatter *dateFormatter = [[NSDateFormatter alloc] init];
    dateFormatter.dateFormat = @"yyyy-MM-dd HH:mm:ss";
    NSTimeZone *gmt = [NSTimeZone timeZoneWithAbbreviation:@"ABC"];
    [dateFormatter setTimeZone:gmt];
    NSString *timeStamp = [dateFormatter stringFromDate:[NSDate date]];
   // NSLog(@"Current Time 2 = %@",timeStamp);
    return timeStamp;
    
}

-(NSString *)getDateStr:(NSDate *)inputDate
{
    NSDateFormatter *dateFormatter = [[NSDateFormatter alloc] init];
    dateFormatter.dateFormat = @"yyyy-MM-dd HH:mm:ss";
    NSTimeZone *gmt = [NSTimeZone timeZoneWithAbbreviation:@"ABC"];
    [dateFormatter setTimeZone:gmt];
    NSString *timeStamp = [dateFormatter stringFromDate:inputDate];
    return timeStamp;
    
}

-(void)postClientDataInBackground:(ClientData *)clientData
{
    @autoreleasepool {
    //[self isWiFiAvailable];
    CommonReturn *ret ;
    ret = [PostClientDataDao postClient:self.appKey deviceInfo:clientData];
        
    if(ret.flag >0)
    {
        if(isLogEnabled)
        {
            NSLog(@"Post Client Data OK: Flag = %d, Msg = %@",ret.flag,ret.msg);
        }
    }
    else 
    {
        if(isLogEnabled)
        {
            NSLog(@"Post Client Data Error: So save to archive. Flag = %d, Msg = %@",ret.flag,ret.msg);
        }
        NSMutableArray *mClientDataArray;
        NSData *oldData = [[NSUserDefaults standardUserDefaults] objectForKey:@"clientDataArray"] ;
        if (oldData!=nil)
        {
            mClientDataArray = [NSKeyedUnarchiver unarchiveObjectWithData:oldData];
        }
        else
        {
            mClientDataArray = [[NSMutableArray alloc] init];
        }
        if(isLogEnabled)
        {
            NSLog(@"archive client data because of BATCH mode");
        }
        [mClientDataArray addObject:clientData];
        if(isLogEnabled)
        {
            NSLog(@"Archived client data = %d",[mClientDataArray count]);
        }
        NSData *newData = [NSKeyedArchiver archivedDataWithRootObject:mClientDataArray];
        [[NSUserDefaults standardUserDefaults] setObject:newData forKey:@"clientDataArray"];
        [[NSUserDefaults standardUserDefaults] synchronize];
    }
    }
}


+(ConfigPreference *)updateOnlineConfig
{
    ConfigPreference * ret ;
    ret = [GetOnlineConfigDao getOnlineConfig:[self getInstance].appKey];
    if (ret.flag>0) {
    }
    return ret;
    
}

uncaughtExceptionHandler(NSException *exception) {    
    NSLog(@"CRASH: %@", exception);      
    NSLog(@"Stack Trace: %@", [exception callStackSymbols]);    
    NSString *stackTrace = [[NSString alloc] initWithFormat:@"%@\n%@",exception,[exception callStackSymbols]]; 
    [[UMSAgent getInstance] saveErrorLog:stackTrace];

}

-(void)postErrorLog:(NSString*)stackTrace
{
    @autoreleasepool {
            if(isLogEnabled)
            {
                NSLog(@"Post error log realtime");
            }
            ErrorLog *errorLog = [[ErrorLog alloc] init];
            errorLog.stackTrace = stackTrace;
            errorLog.appkey = self.appKey;
            errorLog.version = [self getVersion];
            errorLog.time = [self getCurrentTime];
            errorLog.activity = [[NSBundle mainBundle] bundleIdentifier];
            errorLog.osVersion = [[UIDevice currentDevice] systemVersion];
            errorLog.deviceID = [self machineName];
            CommonReturn *ret = [PostClientDataDao postErrorLog:self.appKey errorLog:errorLog];
            if(ret.flag<0)
            {
                [self saveErrorLog:stackTrace];
            }
    }
}

-(void)getSystemLog
{
    aslmsg q, m;
    int i;
    const char*key,*val;
    q = asl_new(ASL_TYPE_QUERY);
    //asl_set_query(q, ASL_KEY_SENDER, "Logger", ASL_QUERY_OP_EQUAL);
    asl_set_query(q, ASL_KEY_LEVEL, "Error", ASL_QUERY_OP_EQUAL);
    NSString *bundleIdentifier = [[NSBundle mainBundle] bundleIdentifier];
    const char* identifier = [bundleIdentifier cString];
    asl_set_query(q, ASL_KEY_FACILITY, identifier, ASL_QUERY_OP_EQUAL);
    aslresponse r = asl_search(NULL, q);
    while(NULL !=(m = aslresponse_next(r)))
    {
        NSMutableDictionary*tmpDict =[NSMutableDictionary dictionary];   
        for(i =0;(NULL !=(key = asl_key(m, i))); i++)   
        {
            NSString *keyString =[NSString stringWithUTF8String:(char*)key];   
            val = asl_get(m, key);
            NSString*string =[NSString stringWithUTF8String:val];     
            [tmpDict setObject:string forKey:keyString];  
        } 
        NSLog(@"%@", tmpDict);
    }
    aslresponse_free(r);
}

+(BOOL)isJailbroken
{
    BOOL jailbroken = NO;  
    NSString *cydiaPath = @"/Applications/Cydia.app";  
    NSString *aptPath = @"/private/var/lib/apt/";  
    if ([[NSFileManager defaultManager] fileExistsAtPath:cydiaPath]) {  
        jailbroken = YES;  
    }  
    if ([[NSFileManager defaultManager] fileExistsAtPath:aptPath]) {  
        jailbroken = YES;  
    }  
    return jailbroken;  
}

+(void)setOnLineConfig:(BOOL)isOnlineConfig
{
    [UMSAgent getInstance].isOnLineConfig = isOnlineConfig;
    if ([UMSAgent getInstance].isOnLineConfig) {
        ConfigPreference *config ;


        config = [self updateOnlineConfig];
        [UMSAgent getInstance].sessionmillis = config.sessionmillis;
        [UMSAgent getInstance].updateOnlyWifi = config.Updateonlywifi;
        [UMSAgent getInstance].policy = [config.reportpolicy intValue];
    }
    else {
        NSLog(@"Not Online");
    }
}

- (void)alertView:(UIAlertView *)alertView didDismissWithButtonIndex:(NSInteger)buttonIndex {
    if(buttonIndex == 1)
    {
        [[UIApplication sharedApplication] openURL:[NSURL URLWithString:updateRet.fileurl]];
    }
}


-(BOOL)isWiFiAvailable
{
    struct ifaddrs *addresses;
    struct ifaddrs *cursor;
    BOOL wiFiAvailable = NO;
    if (getifaddrs(&addresses) != 0) return NO;
    
    cursor = addresses;
    while (cursor != NULL) {
        if (cursor -> ifa_addr -> sa_family == AF_INET
            && !(cursor -> ifa_flags & IFF_LOOPBACK)) // Ignore the loopback address
        {
            // Check for WiFi adapter
            if (strcmp(cursor -> ifa_name, "en0") == 0) {
                wiFiAvailable = YES;
                break;
            }
        }
        cursor = cursor -> ifa_next;
    }
    
    freeifaddrs(addresses);
    return wiFiAvailable;
}

-(void)dealloc
{
     NSSetUncaughtExceptionHandler(NULL);
}

-(NSString*) machineName
{
    struct utsname systemInfo;
    uname(&systemInfo);
    return  [NSString stringWithCString:systemInfo.machine
                              encoding:NSUTF8StringEncoding];
}

+(NSString *)getUMSUDID
{
    NSString * udidInKeyChain = [SFHFKeychainUtils getPasswordForUsername:@"UMSAgentUDID" andServiceName:@"UMSAgent" error:nil];
    if(udidInKeyChain && ![udidInKeyChain isEqualToString:@""])
    {
        return udidInKeyChain;
    }
    else
    {
        NSString *idfa = [[[ASIdentifierManager sharedManager] advertisingIdentifier] UUIDString];
        if(idfa && ![idfa isEqualToString:@""])
        {
            [SFHFKeychainUtils storeUsername:@"UMSAgentUDID" andPassword:idfa forServiceName:@"UMSAgent" updateExisting:NO error:nil];
            return idfa;
        }
        else
        {
            NSString *openUDID = [UMS_OpenUDID value];
            [SFHFKeychainUtils storeUsername:@"UMSAgentUDID" andPassword:openUDID forServiceName:@"UMSAgent" updateExisting:NO error:nil];
            return openUDID;
        }
    }
}

@end
