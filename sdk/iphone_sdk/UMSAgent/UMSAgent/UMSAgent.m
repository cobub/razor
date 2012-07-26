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
#import "Deviceinfo.h"
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
#import <sys/utsname.h>

@interface UMSAgent ()
{
    NSString *appKey;
    ReportPolicy policy;
    BOOL isLogEnabled;
    BOOL isCrashReportEnabled;
    NSDate *startDate;
    NSString *updateOnliWifi;
    NSString *sessionmillis;
    BOOL *isOnlineConfig;

    CheckUpdateReturn *updateRet;
    NSMutableArray *eventArrary;
    NSString *sessionId;
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

+(void)startWithAppKey:(NSString*)appKey
{
    [[UMSAgent getInstance] initWithAppKey:appKey reportPolicy:BATCH];
}

+(void)startWithAppKey:(NSString*)appKey ReportPolicy:(ReportPolicy)policy
{
    [[UMSAgent getInstance] initWithAppKey:appKey reportPolicy:policy];
}

+ (void)setIsLogEnabled:(BOOL)isLogEnabled
{
    [UMSAgent getInstance].isLogEnabled = isLogEnabled;
}


-(void)initWithAppKey:(NSString*)applicationKey reportPolicy:(ReportPolicy)m_policy
{    
    self.appKey = applicationKey;
    self.policy = m_policy;
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
    self.sessionId = [self md5:currentTime];
    if(isLogEnabled)
    {
        NSLog(@"Get Session ID = %@",sessionId);
    }
    NSSetUncaughtExceptionHandler(&uncaughtExceptionHandler);
    [self performSelectorInBackground:@selector(postClientDataInBackground) withObject:nil];
    //Process archived logs
    [self performSelectorInBackground:@selector(processArchivedLogs) withObject:nil];
}



+(void)startTracPage:(NSString*)page_name
{
    [[UMSAgent getInstance] performSelectorInBackground:@selector(recordStartTime:) withObject:page_name];
}

-(void)recordStartTime:(NSString*) page_name
{
    @autoreleasepool {
        NSDate *pageStartDate = [[NSDate date] copy];
        [[NSUserDefaults standardUserDefaults] setObject:pageStartDate forKey:page_name];
        [[NSUserDefaults standardUserDefaults] synchronize];
    }
}

+(void)endTracPage:(NSString*)page_name
{
    if([UMSAgent getInstance].policy == REALTIME)
    {
        if([UMSAgent getInstance].isLogEnabled)
        {
            NSLog(@"Commit using Time of page %@",page_name);
        }
        [[UMSAgent getInstance] performSelectorInBackground:@selector(commitUsingTime:) withObject:page_name];
    }
    else
    {
        if([UMSAgent getInstance].isLogEnabled)
        {
            NSLog(@"Save Activity using time to cache of %@",page_name);
        }
        [[UMSAgent getInstance] performSelectorInBackground:@selector(saveActivityUsingTime:) withObject:page_name];
    }

}

- (void)resignActive:(NSNotification *)notification 
{
    NSString *page_name = [[NSBundle mainBundle] bundleIdentifier];
    if(policy == REALTIME)
    {
        if(isLogEnabled)
        {
            NSLog(@"Commit using Time");
        }
        [self performSelectorInBackground:@selector(commitUsingTime:) withObject:page_name];
    }
    else
    {
        if(isLogEnabled)
        {
            NSLog(@"Save Activity using time to cache");
        }
        [self performSelectorInBackground:@selector(saveActivityUsingTime:) withObject:page_name];
    }
}

- (void)becomeActive:(NSNotification *)notification 
{
    if(isLogEnabled)
    {
        NSLog(@"Application become active");
    }
    NSString *page_name = [[NSBundle mainBundle] bundleIdentifier];
    NSDate *pageStartDate = [[NSDate date] copy];
    [[NSUserDefaults standardUserDefaults] setObject:pageStartDate forKey:page_name];
    [[NSUserDefaults standardUserDefaults] synchronize];
    NSString *currentTime = [[NSString alloc] initWithFormat:@"%f",[[NSDate date] timeIntervalSince1970]];
    self.sessionId = [self md5:currentTime];
    if(isLogEnabled)
    {
        NSLog(@"Current session ID = %@",sessionId);
    }
    
    [self performSelectorInBackground:@selector(postClientDataInBackground) withObject:nil];
    //Process archived logs
    [self performSelectorInBackground:@selector(processArchivedLogs) withObject:nil];
    
    if(isLogEnabled)
    {
        NSLog(@"Application Resign Active");
    }
    
}

-(void)commitUsingTime:(NSString*)pageName
{
    @autoreleasepool 
    {
        NSString *session_mills = self.sessionId;
        NSString *end_mils = [self getCurrentTime];
        NSDate *pageStartDate = [[NSUserDefaults standardUserDefaults] objectForKey:pageName];
        if(pageStartDate!=nil)
        {
            NSString *start_mils = [self getDateStr:pageStartDate];
            NSTimeInterval duration = (-[startDate timeIntervalSinceNow])*1000;
            NSString *durationStr = [[NSString alloc] initWithFormat:@"%f",duration];
            NSString *activities = pageName;
            NSString *appVersion = [self getVersion];
            [PostClientDataDao postUsingTime:appKey sessionMills:session_mills startMils:start_mils endMils:end_mils duration:durationStr activity:activities version:appVersion];
            [[NSUserDefaults standardUserDefaults] removeObjectForKey:pageName];
            [[NSUserDefaults standardUserDefaults] synchronize];
        }
        else
        {
           if(isLogEnabled)
           {
                NSLog(@"Page Start time not found. in commitUsingTime pagename = %@",pageName);
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

- (void)saveActivityUsingTime:(NSString*)pageName
{
    @autoreleasepool 
    {
        ActivityLog *acLog = [[ActivityLog alloc] init];
        acLog.sessionMils = self.sessionId;
        NSDate *pageStartDate = [[NSUserDefaults standardUserDefaults] objectForKey:pageName];
        if(pageStartDate!=nil)
        {
            NSString *start_mils = [self getDateStr:pageStartDate];
            acLog.startMils = start_mils;
            [[NSUserDefaults standardUserDefaults] removeObjectForKey:pageName];
            [[NSUserDefaults standardUserDefaults] synchronize];
        }
        else
        {
            if(isLogEnabled)
            {
                 NSLog(@"Page Start time not found. in saveActivityUsingTime pagename = %@",pageName);
            }   
            return;
        }
        acLog.endMils = [self getCurrentTime];
        NSTimeInterval duration = (-[startDate timeIntervalSinceNow])*1000;
        acLog.duration = [[NSString alloc] initWithFormat:@"%f",duration];
        acLog.activity = pageName;
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
        NSString *version = [[NSString alloc] initWithFormat:@"版本更新%@",retWrapper.version];
        UIAlertView *alert = [[UIAlertView alloc] initWithTitle: version
                                                        message: retWrapper.description
                                                       delegate: self
                                              cancelButtonTitle:@"取消"
                                              otherButtonTitles:@"确定", nil];
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


-(void) processEvent:(Event *)event
{
    [self performSelectorInBackground:@selector(postEventInBackGround:) withObject:event];
}

-(void) processArchivedLogs
{
    @autoreleasepool {
        NSMutableArray *eventArray = [self getArchiveEvent];
        NSMutableArray *activityLogArray = [self getArchiveActivityLog];
        NSMutableArray *errorLogArray = [self getArchiveErrorLog];
        if([eventArray count]>0 || [activityLogArray count]>0 || [errorLogArray count]>0)
        {
            NSMutableDictionary *requestDic = [[NSMutableDictionary alloc] init];
            [requestDic setObject:appKey forKey:@"appkey"];
            if([eventArray count]>0)
            {
                [requestDic setObject:eventArray forKey:@"eventInfo"];
            }
            
            if([activityLogArray count] >0)
            {
                [requestDic setObject:activityLogArray forKey:@"activityInfo"];
            }
            
            if([errorLogArray count]>0)
            {
                [requestDic setObject:errorLogArray forKey:@"errorInfo"];
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
                [[NSUserDefaults standardUserDefaults] removeObjectForKey:@"activityLog"];
                [[NSUserDefaults standardUserDefaults] removeObjectForKey:@"errorLog"];
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

-(NSString *) getVersion
{
    NSString *appVersion = [[[NSBundle mainBundle] infoDictionary] objectForKey:@"CFBundleVersion"];
    return appVersion;
}

-(Deviceinfo *)getDeviceInfo
{
    Deviceinfo  *info = [[Deviceinfo alloc] init];
    info.platform = [[UIDevice currentDevice] systemName];
    info.devicename = [self machineName];
    info.modulename = [[UIDevice currentDevice] model];
    info.os_version = [[UIDevice currentDevice] systemVersion];
    info.time = [self getCurrentTime];
    if([UMSAgent isJailbroken])
    {
        info.isJailbroken = @"1";
    }
    else {
        info.isJailbroken = @"0";
    }
    
    CGRect rect = [[UIScreen mainScreen] bounds];
    CGFloat scale = [[UIScreen mainScreen] scale];
    info.resolution = [[NSString alloc] initWithFormat:@"%.fx%.f",rect.size.width*scale,rect.size.height*scale];
    //Using open UDID 
    info.deviceID = [OpenUDID value];
    NSUserDefaults *defaults = [NSUserDefaults standardUserDefaults]; 
    NSArray *languages = [defaults objectForKey:@"AppleLanguages"]; 
    info.language = [languages objectAtIndex:0];
    
    
    CTTelephonyNetworkInfo*netInfo =[[CTTelephonyNetworkInfo alloc] init];
    CTCarrier*carrier =[netInfo subscriberCellularProvider];
    NSString*mcc =[carrier mobileCountryCode];
    NSString*mnc =[carrier mobileNetworkCode];
    info.MCCMNC = [mcc stringByAppendingString:mnc];
    
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
    NSLog(@"Current Time 2 = %@",timeStamp);
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

-(void)postClientDataInBackground
{
    @autoreleasepool {
    [self isWiFiAvailable];
    CommonReturn *ret ;
    ret = [PostClientDataDao postClient:self.appKey deviceInfo:[self getDeviceInfo]];
    if(ret.flag <0)
    {
        if(isLogEnabled)
        {
            NSLog(@"Post Client Data Error: Flag = %d, Msg = %@",ret.flag,ret.msg);
        }
    }
    else 
    {
        if(isLogEnabled)
        {
            NSLog(@"Post Client Data OK: Flag = %d, Msg = %@",ret.flag,ret.msg);
        }
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
//    if([UMSAgent getInstance].policy == REALTIME)
//    {
//        [[UMSAgent getInstance] performSelectorInBackground:@selector(postErrorLog:) withObject:stackTrace];
//    }
//    else
//    {
//        [[UMSAgent getInstance] performSelectorInBackground:@selector(saveErrorLog:) withObject:stackTrace];
//    }
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
            [PostClientDataDao postErrorLog:self.appKey errorLog:errorLog];
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
        NSLog(@"本地配置");
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
//    if([deviceName isEqualToString:@"i386"])
//    {
//        return @"iPod Touch";
//    }
//    if([deviceName isEqualToString:@"iPod2,1"])
//    {
//        return @"iPod Touch 2";
//    }
//    if([deviceName isEqualToString:@"iPod3,1"])
//    {
//        return @"iPod Touch 3";
//    }
//    if([deviceName isEqualToString:@"iPod4,1"])
//    {
//        return @"iPod Touch 4";
//    }
//    if([deviceName isEqualToString:@"iPhone1,1"])
//    {
//        return @"iPhone";
//    }
//    if([deviceName isEqualToString:@"iPhone1,2"])
//    {
//        return @"iPhone 3G";
//    }
//    if([deviceName isEqualToString:@"iPhone2,1"])
//    {
//        return @"iPhone 3GS";
//    }
//    if([deviceName isEqualToString:@"iPad1,1"])
//    {
//        return @"iPad";
//    }
//    if([deviceName isEqualToString:@"iPad2,1"])
//    {
//        return @"iPad 2";
//    }
//    if([deviceName isEqualToString:@"iPhone3,1"])
//    {
//        return @"iPhone 4";
//    }
//    if([deviceName isEqualToString:@"iPhone4,1"])
//    {
//        return @"iPhone 4S";
//    }
}

@end
