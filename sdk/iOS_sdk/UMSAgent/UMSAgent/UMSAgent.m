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
#import "ClientDataDao.h"
#import "TagDao.h"
#import "EventDao.h"
#import "Event.h"
#import "Global.h"
#import "ConfigDao.h"
#import "asl.h"
#import <SystemConfiguration/SystemConfiguration.h>
#import <arpa/inet.h>
#import <ifaddrs.h>
#import <net/if.h>
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
#import "ErrorDao.h"
#import "UsingLogDao.h"
#import "UIDevice+Analysis.h"
#import "AppInfo.h"

#import <mach-o/dyld.h>
#import <mach-o/arch.h>
#import <mach-o/loader.h>
#include <sys/types.h>
#include <sys/sysctl.h>
#include <mach/machine.h>
#import "UncaughtExceptionHandler.h"

@interface UMSAgent ()
{
    NSString *appKey;
    ReportPolicy policy;
    BOOL isCrashReportEnabled;
    
    //For interval policy
    NSDate *postTimerFireDate;
    NSTimer *postTimer;
    
    NSDate *startDate;
    NSDate *sessionStopDate;
    
    int updateOnlyWifi;
    int sessionmillis;
    BOOL isOnLineConfig;
    int sendInterval;
    long long maxCacheSize;
    
    CheckUpdateReturn *updateRet;
    //    NSMutableArray *eventArrary;
    NSString *sessionId;
    NSString *pageName;
}

@property (nonatomic) ReportPolicy policy;
@property (nonatomic) BOOL isCrashReportEnabled;
@property (nonatomic,strong) NSString *appKey;
@property (nonatomic) int updateOnlyWifi;
@property (nonatomic) int sessionmillis;
@property (nonatomic) int sendInterval;
@property (nonatomic) BOOL isOnLineConfig;
@property (nonatomic) CheckUpdateReturn *updateRet;
@property (nonatomic,strong) NSDate *startDate;
@property (nonatomic,strong) NSString *sessionId;
@property (nonatomic,strong) NSString *pageName;
@property (nonatomic,strong) NSDate *sessionStopDate;
@property (nonatomic,strong) NSDate *postTimerFireDate;
@property (nonatomic) long long maxCacheSize;

-(BOOL)isWiFiAvailable;

@end

static NSString *LIB_VERSION = @"1.0";

@implementation UMSAgent
@synthesize policy;
@synthesize isLogEnabled;
@synthesize isCrashReportEnabled;
@synthesize updateOnlyWifi,sessionmillis,isOnLineConfig,sendInterval;
@synthesize updateRet;
@synthesize appKey;
@synthesize startDate;
@synthesize sessionId;
@synthesize pageName;
@synthesize sessionStopDate;
@synthesize postTimerFireDate;
@synthesize maxCacheSize;

#define kClientDataArray @"clientDataArray"
#define kActivityLog @"activityLog"
#define kErrorLog @"errorLog"
#define kEventArray @"eventArray"
#define kTagArray @"tagArray"


#pragma mark - Singleton & Initialization
+ (UMSAgent*)getInstance
{
    static UMSAgent *instance = nil;
    if(instance == nil)
    {
        instance = [[[self class] alloc] init];
        instance.isLogEnabled = DEFAULT_ENABLE_LOG;
        instance.isCrashReportEnabled = DEFAULT_ENABLE_CRASH_REPORT;
        instance.policy = DEFAUT_POLICY;
        instance.sessionmillis = DEFAULT_SESSIONMILLIS;
        instance.updateOnlyWifi =  DEFAULT_UPDATE_ONLY_WIFI;
        instance.sendInterval = DEFAULT_INTERVAL_TIME;
    }
    return instance;
}

+ (void)startWithAppKey:(NSString *)appKey serverURL:(NSString *)serverURL {
    // Use REALTIME policy by default
    [[UMSAgent getInstance] initWithAppKey:appKey
                              reportPolicy:REALTIME
                                 serverURL:serverURL];
}

+ (void)startWithAppKey:(NSString *)appKey
           ReportPolicy:(ReportPolicy)policy
              serverURL:(NSString *)serverURL {
    [[UMSAgent getInstance] initWithAppKey:appKey
                              reportPolicy:policy
                                 serverURL:serverURL];
}

- (void)initWithAppKey:(NSString *)applicationKey
          reportPolicy:(ReportPolicy)m_policy
             serverURL:(NSString *)m_serverURL {
    self.appKey = applicationKey;
    // If use online config, the policy settings for this method are disabled.
    // Just use online configuration policy
    // Developer must set onlineconfig before invoke startWithAppKey
    if (![UMSAgent getInstance].isOnLineConfig) {
        self.policy = m_policy;
    }
    
    [Global setBaseURL:m_serverURL];
    
    // Update Online parameters
    if ([UMSAgent getInstance].isOnLineConfig) {
        [[UMSAgent getInstance] updateOnlineConfig];
    }
    
    //Update custom parameters
    //[self updateCustomParams];
    
    // Monitor notification,resignActive will be invoked when home key clicked.
    // becomeActive will be invoked when restore to application
    NSNotificationCenter *notifCenter = [NSNotificationCenter defaultCenter];
    [notifCenter addObserver:self
                    selector:@selector(resignActive:)
                        name:UIApplicationWillResignActiveNotification
                      object:nil];
    [notifCenter addObserver:self
                    selector:@selector(becomeActive:)
                        name:UIApplicationDidBecomeActiveNotification
                      object:nil];
    
    // Record user start application time
    self.startDate = [[NSDate date] copy];
    
    // Generate sessionID
    self.sessionId = [self generateSessionId];
    
    //   if(isLogEnabled)
    //  {
    NSLog(@"Get Session ID = %@", sessionId);
    // }
    
    //NSSetUncaughtExceptionHandler(&uncaughtExceptionHandler);
    InstallUncaughtExceptionHandler();
    
    [self postClientData];
    [self postCacheData];
    // Start timer for INTERVAL policy
    if ([UMSAgent getInstance].policy == INTERVAL) {
        
        postTimer = [NSTimer
                     scheduledTimerWithTimeInterval:[UMSAgent getInstance].sendInterval * 60
                     target:self
                     selector:@selector(postTimerFired:)
                     userInfo:nil
                     repeats:YES];
    }
    postTimerFireDate = [[NSDate date] copy];
}



#pragma mark - activities
//Trace page, must invoked on viewDidAppear method.
+ (void)tracePage:(NSString*)page_name
{
    @synchronized(self)
    {
        [[UMSAgent getInstance] performSelectorInBackground:@selector(processPage:) withObject:page_name];
    }
}

- (void)processPage:(NSString*) page_name
{
    @autoreleasepool {
        NSString *newPageName = [[NSString alloc] initWithString:page_name];
        if(self.pageName!=nil && ![self.pageName isEqualToString:@""])
        {
            if([self.pageName isEqualToString:newPageName])
            {
                //If new page is equal to old pagename. ex: web page refresh, just ignore
                return;
            }
            else
            {
                [self saveActivityUsingTime:self.pageName];
            }
        }
        
        //Record page start time
        [self recordStartTime:page_name];
    }
}

+ (void)startTracPage:(NSString*)page_name
{
    if([UMSAgent getInstance].isLogEnabled)
    {
        NSLog(@"Start Trace Page with page name %@",page_name);
    }
    
    [[UMSAgent getInstance] performSelectorInBackground:@selector(recordStartTime:) withObject:page_name];
}

- (void)recordStartTime:(NSString*) page_name
{
    @autoreleasepool {
        NSString *newPageName = [[NSString alloc] initWithString:page_name];
        self.pageName = newPageName;
        NSDate *pageStartDate = [[NSDate date] copy];
        [[NSUserDefaults standardUserDefaults] setObject:pageStartDate forKey:page_name];
        [[NSUserDefaults standardUserDefaults] synchronize];
    }
}

+ (void)endTracPage:(NSString*)page_name
{
    if([UMSAgent getInstance].isLogEnabled)
    {
        NSLog(@"End Trace Page with page name %@",page_name);
    }
    [[UMSAgent getInstance] performSelectorInBackground:@selector(saveActivityUsingTime:) withObject:page_name];
}

- (void)saveActivityUsingTime:(NSString*)currentPageName
{
    @autoreleasepool
    {
        ActivityLog *acLog = [[ActivityLog alloc] init];
        acLog.sessionID = self.sessionId;
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
        
        acLog.duration = [[NSString alloc] initWithFormat:@"%d",(int)duration];
        acLog.activity = currentPageName;
        acLog.version = [self getVersion];
        acLog.lib_version = LIB_VERSION;
        if(acLog && isLogEnabled)
        {
            NSLog(@"acLog sessionMils = %@",acLog.sessionID);
        }
        //		NSData *activityLogData = [[NSUserDefaults standardUserDefaults] objectForKey:@"activityLog"] ;
        NSData *activityLogData = [UMSAgent getArchivedActivitiesWithLastSessionId:acLog.sessionID];
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
            NSLog(@"Activity Log array size = %lu",(unsigned long)[activityLogArray count]);
        }
        //		self.pageName = @"";
        NSData *newActivityData = [NSKeyedArchiver archivedDataWithRootObject:activityLogArray];
        //		[[NSUserDefaults standardUserDefaults] setObject:newActivityData forKey:@"activityLog"];
        //		[[NSUserDefaults standardUserDefaults] synchronize];
        [NSKeyedArchiver archiveRootObject:newActivityData toFile:[UMSAgent getFilePath:kActivityLog]];
    }
}

+ (NSData*) getArchivedActivitiesWithLastSessionId: (NSString*)sessionId {
    NSData *result = nil;
    NSString *path = [UMSAgent getFilePath:kActivityLog];
    long long threshold = [UMSAgent getInstance].maxCacheSize * 1024 * 1024;
    if ([UMSAgent fileSizeAtPath:path] < threshold) {
        result = [NSKeyedUnarchiver unarchiveObjectWithFile:path];
    } else {
        NSData *oldData = [NSKeyedUnarchiver unarchiveObjectWithFile:path];
        NSArray *oldArr = [NSKeyedUnarchiver unarchiveObjectWithData:oldData];
        NSMutableArray *newArr = [[NSMutableArray alloc]init];
        for (ActivityLog *ac in oldArr) {
            if ([ac.sessionID isEqual:sessionId]) {
                [newArr addObject:ac];
            } else {
            }
        }
        //		[UMSAgent removeArchivedFile:kActivityLog];
        NSData* newData = [NSKeyedArchiver archivedDataWithRootObject:newArr];
        result = newData;
    }
    return result;
}


- (NSString *)getCurrentActivityName
{
    if(self.pageName)
    {
        return self.pageName;
    }
    
    return @"";
}

- (void)resignActive:(NSNotification *)notification
{
    //Application go to background
    if(self.pageName!=nil)
    {
        [self saveActivityUsingTime:self.pageName];
    }
    
    //Check if data need to be post under INTERVAL policy
    if([UMSAgent getInstance].policy == INTERVAL)
    {
        [self postDataInterval];
        [postTimer setFireDate:[NSDate distantFuture]];
    }
    sessionStopDate = [[NSDate date] copy];
    if(isLogEnabled)
    {
        NSLog(@"Resign Active: click home button or lose focus. End Trace Page and save session stop date.");
    }
}

- (void)becomeActive:(NSNotification *)notification
{
    //Restore application
    //[self updateCustomParams];
    //Refresh timer
    
    if([UMSAgent getInstance].policy == INTERVAL)
    {
        [postTimer setFireDate:[NSDate distantPast]];
    }
    if(sessionStopDate!=nil)
    {
        NSTimeInterval intervalSinceResign = -[sessionStopDate timeIntervalSinceNow];
        if(intervalSinceResign + 0.0000001 > [UMSAgent getInstance].sessionmillis)
        {
            //If time interval since resign more than sessionmillis, take as new session.
            self.sessionId = [self generateSessionId];
            
            //Post clientdata since taking as new session
            [self postClientData];
            
            if([UMSAgent getInstance].policy == INTERVAL)
            {
                [self postDataInterval];
            }
            else
            {
                //Realtime & Batch mode, send cache Data
                [self postCacheData];
            }
            
            if(self.pageName && ![self.pageName isEqualToString:@""])
            {
                //Take last pagename as new pagename
                NSString *resumePrePageName = self.pageName;
                pageName = nil;
                [UMSAgent tracePage:resumePrePageName];
            }
            if(isLogEnabled)
            {
                NSLog(@"Stop session more than session mills seconds, so consider as new session id.");
            }
        }
        else
        {
            if(isLogEnabled)
            {
                NSLog(@"Session since last resign active less than 30 seconds, so consider as old session.");
            }
        }
    }
    else
    {
        self.sessionId = [self generateSessionId];
    }
    if(isLogEnabled)
    {
        NSLog(@"Application become Active,Current session ID = %@",sessionId);
    }
}


#pragma mark - error log
+ (void)saveErrorLog:(NSString*)stackTrace
{
    @autoreleasepool {
        if([UMSAgent getInstance].isLogEnabled)
        {
            NSLog(@"Save Error Log...");
        }
        ErrorLog *errorLog = [[ErrorLog alloc] init];
        errorLog.stackTrace = stackTrace;
        errorLog.appkey = [UMSAgent getInstance].appKey;
        errorLog.version = [[UMSAgent getInstance] getVersion];
        errorLog.time = [[UMSAgent getInstance] getCurrentTime];
        //Current Activity
        if([UMSAgent getInstance].pageName)
        {
            errorLog.activity = [UMSAgent getInstance].pageName;
        }
        else
        {
            errorLog.activity = @"";
        }
        errorLog.osVersion = [[UIDevice currentDevice] systemVersion];
        errorLog.uuID = [[UncaughtExceptionHandler ExecutableUUID] UUIDString];
        errorLog.cpt = [UncaughtExceptionHandler getCPUType];
        errorLog.bim = [UncaughtExceptionHandler getBinary];
        errorLog.cpt = [UncaughtExceptionHandler getCPUType];
        errorLog.bim = [UncaughtExceptionHandler getBinary];
        errorLog.lib_version = LIB_VERSION;
        //        NSData *errorLogData = [[NSUserDefaults standardUserDefaults] objectForKey:@"errorLog"] ;
        NSData *errorLogData = [UMSAgent getArchivedLogFromFile:kErrorLog];
        NSMutableArray * errorLogArray = [[NSMutableArray alloc] init ];
        if (errorLogData!=nil)
        {
            errorLogArray = [NSKeyedUnarchiver unarchiveObjectWithData:errorLogData];
        }
        else {
            errorLogArray = [[NSMutableArray alloc] init ];
        }
        [errorLogArray addObject:errorLog];
        if([UMSAgent getInstance].isLogEnabled)
        {
            NSLog(@"Error Log array size = %lu",(unsigned long)[errorLogArray count]);
        }
        NSData *newErrorData = [NSKeyedArchiver archivedDataWithRootObject:errorLogArray];
        //        [[NSUserDefaults standardUserDefaults] setObject:newErrorData forKey:@"errorLog"];
        //        [[NSUserDefaults standardUserDefaults] synchronize];
        [UMSAgent checkSizeAndSaveObject:newErrorData ToFile:kErrorLog];
    }
}


+ (void)postErrorLog:(NSString*)stackTrace
{
    
    if([UMSAgent getInstance].isLogEnabled)
    {
        NSLog(@"Post error log realtime");
    }
    ErrorLog *errorLog = [[ErrorLog alloc] init];
    errorLog.stackTrace = stackTrace;
    errorLog.appkey = [UMSAgent getInstance].appKey;
    errorLog.version = [[UMSAgent getInstance]getVersion];
    errorLog.time = [[UMSAgent getInstance] getCurrentTime];
    errorLog.activity = [[NSBundle mainBundle] bundleIdentifier];
    errorLog.osVersion = [[UIDevice currentDevice] systemVersion];
    errorLog.uuID = [[UncaughtExceptionHandler ExecutableUUID] UUIDString];
    errorLog.cpt = [UncaughtExceptionHandler getCPUType];
    errorLog.bim = [UncaughtExceptionHandler getBinary];
    errorLog.cpt = [UncaughtExceptionHandler getCPUType];
    errorLog.bim = [UncaughtExceptionHandler getBinary];
    errorLog.lib_version = LIB_VERSION;
    [[UMSAgent getInstance ] archiveError:errorLog];
    
}

- (void)archiveError:(ErrorLog *)error{
    NSMutableArray *mErrorArray;
    if (self.policy == BATCH || self.policy == INTERVAL) {
        
        NSData *oldData  = [UMSAgent getArchivedLogFromFile:kErrorLog];
        if (oldData!=nil) {
            mErrorArray = [NSKeyedUnarchiver unarchiveObjectWithData:oldData];
        }else{
            mErrorArray = [[NSMutableArray alloc]init];
            
        }
        if(isLogEnabled)
        {
            NSLog(@"archive error because of BATCH mode");
        }
        [mErrorArray addObject:error];
        if (isLogEnabled) {
            NSLog(@"Archived error count = %lu",(unsigned long)[mErrorArray count]);
        }
        NSData *newData = [NSKeyedArchiver archivedDataWithRootObject:mErrorArray];
        [UMSAgent checkSizeAndSaveObject:newData ToFile:kErrorLog];
    }
    else{
        [self processError:error];
    }
    
}
- (void)processError:(ErrorLog *)error{
    [self performSelectorInBackground:@selector(postErrorLogInBackGround:) withObject:error];
}

- (void)postErrorLogInBackGround:(ErrorLog *)error{
    
    @autoreleasepool {
        
        CommonReturn *ret;
        ret = [ErrorDao postErrorLog:self.appKey errorLog:error];
        if(ret.flag >0)
        {
            if(isLogEnabled)
            {
                NSLog(@"Post ErrorLog OK: Flag = %d, Msg = %@",ret.flag,ret.msg);
            }
        }
        
        if (ret.flag < 0) {
            NSData *oldData = [UMSAgent getArchivedLogFromFile:kErrorLog];
            NSMutableArray *array = [[NSMutableArray alloc]init];
            if (oldData != nil) {
                array = [NSKeyedUnarchiver unarchiveObjectWithData:oldData];
            }
            [array addObject:error];
            NSData *newData = [NSKeyedArchiver archivedDataWithRootObject:array];
            
            [UMSAgent checkSizeAndSaveObject:newData ToFile:kErrorLog];
        }
    }
}




#pragma mark - Event
+ (void)postEvent:(NSString *)event_id
{
    Event *event =[[Event alloc] init];
    event.event_id = event_id;
    event.activity = [[UMSAgent getInstance] getCurrentActivityName];
    event.label = @"";
    event.time = [[UMSAgent getInstance] getCurrentTime];
    event.version = [[UMSAgent getInstance] getVersion];
    event.acc = 1;
    event.lib_version = LIB_VERSION;
    [[UMSAgent getInstance] archiveEvent:event];
}

+ (void)postEvent:(NSString *)event_id label:(NSString *)label
{
    Event *event = [[Event alloc] init];
    event.event_id = event_id;
    event.time = [[UMSAgent getInstance] getCurrentTime];
    event.acc = 1;
    event.jsonstr = @"";
    event.version = [[UMSAgent getInstance] getVersion];
    event.activity = [[UMSAgent getInstance] getCurrentActivityName];
    event.label = label;
    event.lib_version = LIB_VERSION;
    [[UMSAgent getInstance] archiveEvent:event];
    
}

+ (void)postGenericEvent:(NSString *)label acc:(NSInteger)acc
{
    Event *event = [[Event alloc] init];
    event.event_id = @"default_maadmin_event";
    event.time = [[UMSAgent getInstance] getCurrentTime];
    event.acc = (int)acc ;
    event.jsonstr = @"";
    event.version = [[UMSAgent getInstance] getVersion];
    event.activity = [[UMSAgent getInstance] getCurrentActivityName];
    event.label = label;
    event.lib_version = LIB_VERSION;
    [[UMSAgent getInstance] archiveEvent:event];
}

+ (void)postEvent:(NSString *)event_id acc:(NSInteger)acc
{
    Event *event = [[Event alloc] init];
    event.event_id = event_id;
    event.time = [[UMSAgent getInstance] getCurrentTime];
    event.acc = (int)acc;
    event.version = [[UMSAgent getInstance] getVersion];
    event.activity = [[UMSAgent getInstance] getCurrentActivityName];
    event.label = @"";
    event.jsonstr = @"";
    event.lib_version = LIB_VERSION;
    [[UMSAgent getInstance] archiveEvent:event];
    
}

+ (void)postEvent:(NSString *)event_id label:(NSString *)label acc:(NSInteger)acc
{
    Event *event = [[Event alloc] init];
    event.event_id = event_id;
    event.time = [[UMSAgent getInstance] getCurrentTime];
    event.acc = (int)acc;
    event.activity = [[UMSAgent getInstance] getCurrentActivityName];
    event.version = [[UMSAgent getInstance] getVersion];
    event.label = label;
    event.jsonstr = @"";
    event.lib_version = LIB_VERSION;
    [[UMSAgent getInstance] archiveEvent:event];
}

+ (void)postEventJSON:(NSString*)event_id json:(NSString*)jsonStr
{
    Event *event = [[Event alloc] init];
    event.event_id = event_id;
    event.time = [[UMSAgent getInstance] getCurrentTime];
    event.acc = 1;
    event.activity = [[UMSAgent getInstance] getCurrentActivityName];
    event.version = [[UMSAgent getInstance] getVersion];
    event.label = @"";
    event.jsonstr = [jsonStr stringByReplacingPercentEscapesUsingEncoding:NSUTF8StringEncoding];
    event.lib_version = LIB_VERSION;
    [[UMSAgent getInstance] archiveEvent:event];
}

- (void)archiveEvent:(Event *)event
{
    NSMutableArray *mEventArray;
    if (self.policy == BATCH || self.policy == INTERVAL) {      /////
        //		NSData *oldData = [[NSUserDefaults standardUserDefaults] objectForKey:@"eventArray"] ;
        NSData *oldData = [UMSAgent getArchivedLogFromFile:kEventArray];
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
            NSLog(@"Archived event count = %lu",(unsigned long)[mEventArray count]);
        }
        NSData *newData = [NSKeyedArchiver archivedDataWithRootObject:mEventArray];
        //		[[NSUserDefaults standardUserDefaults] setObject:newData forKey:@"eventArray"];
        //		[[NSUserDefaults standardUserDefaults] synchronize];
        [UMSAgent checkSizeAndSaveObject:newData ToFile:kEventArray];
    }
    else
    {
        [self processEvent:event];
    }
}


- (void) processEvent:(Event *)event
{
    [self performSelectorInBackground:@selector(postEventInBackGround:) withObject:event];
}


- (void)postEventInBackGround:(Event *)event
{
    @autoreleasepool {
        CommonReturn *ret ;
        ret = [EventDao postEvent:self.appKey event:event];
        if(ret.flag >0)
        {
            if(isLogEnabled)
            {
                NSLog(@"Post Event OK: Flag = %d, Msg = %@",ret.flag,ret.msg);
            }
        }
        
        if (ret.flag<0)
        {
            //			NSData *oldData = [[NSUserDefaults standardUserDefaults] objectForKey:@"eventArray"] ;
            NSData *oldData = [UMSAgent getArchivedLogFromFile:kEventArray];
            NSMutableArray * array = [[NSMutableArray alloc] init];
            
            if (oldData!=nil)
            {
                array = [NSKeyedUnarchiver unarchiveObjectWithData:oldData];
            }
            [array addObject:event];
            NSData *newData = [NSKeyedArchiver archivedDataWithRootObject:array];
            //			[[NSUserDefaults standardUserDefaults] setObject:newData forKey:@"eventArray"];
            //			[[NSUserDefaults standardUserDefaults] synchronize];
            [UMSAgent checkSizeAndSaveObject:newData ToFile:kEventArray];
        }
    }
}

#pragma mark - tags
+ (void)postTag:(NSString *)tag
{
    Tag *tags = [[Tag alloc] init];
    tags.tag = tag;
    tags.productkey = [[UMSAgent getInstance] appKey];
    tags.deviceid = [UMSAgent getUMSUDID];
    tags.lib_version = LIB_VERSION;
    NSString *userid = [[NSUserDefaults standardUserDefaults] objectForKey:@"useridentifier"];
    if (userid==nil) {
        userid = @"";
    }
    
    tags.useridentifier = userid;
    
    [[UMSAgent getInstance] archiveTag:tags];
}

- (void)archiveTag:(Tag *)tag
{
    NSMutableArray *mTagArray;
    if (self.policy == REALTIME)
    {
        [self processTag:tag];
    }
    else
    {
        //        NSData *oldData = [[NSUserDefaults standardUserDefaults] objectForKey:@"tagArray"] ;
        NSData *oldData = [UMSAgent getArchivedLogFromFile:kTagArray];
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
            NSLog(@"Archived tag count = %lu",(unsigned long)[mTagArray count]);
        }
        NSData *newData = [NSKeyedArchiver archivedDataWithRootObject:mTagArray];
        //        [[NSUserDefaults standardUserDefaults] setObject:newData forKey:@"tagArray"];
        //        [[NSUserDefaults standardUserDefaults] synchronize];
        [UMSAgent checkSizeAndSaveObject:newData ToFile:kTagArray];
    }
}

- (void) processTag:(Tag *)tag
{
    [self performSelectorInBackground:@selector(postTagInBackGround:) withObject:tag];
}

- (void)postTagInBackGround:(Tag *)tag
{
    @autoreleasepool {
        CommonReturn *ret ;
        ret = [TagDao postTag:self.appKey tag:tag];
        if(ret.flag >0)
        {
            if(isLogEnabled)
            {
                NSLog(@"Post Tag OK: Flag = %d, Msg = %@",ret.flag,ret.msg);
            }
        }
        
        if (ret.flag<0)
        {
            //            NSData *oldData = [[NSUserDefaults standardUserDefaults] objectForKey:@"tagArray"] ;
            NSData *oldData = [UMSAgent getArchivedLogFromFile:kTagArray];
            NSMutableArray * array = [[NSMutableArray alloc] init];
            
            if (oldData!=nil)
            {
                array = [NSKeyedUnarchiver unarchiveObjectWithData:oldData];
            }
            [array addObject:tag];
            NSData *newData = [NSKeyedArchiver archivedDataWithRootObject:array];
            //            [[NSUserDefaults standardUserDefaults] setObject:newData forKey:@"tagArray"];
            //            [[NSUserDefaults standardUserDefaults] synchronize];
            [UMSAgent checkSizeAndSaveObject:newData ToFile:kTagArray];
        }
    }
}

#pragma mark - processArchivedLogs
- (void)postCacheData
{
    //Process archived logs after post ClientData
    [self performSelectorInBackground:@selector(processArchivedLogs) withObject:nil];
}

- (void) processArchivedLogs
{
    @autoreleasepool {
        NSMutableArray *eventArray = [EventDao getArchiveEvent:appKey];
        NSMutableArray *errorLogArray = [ErrorDao getArchiveErrorLog];
        NSMutableArray *clientDataArray = [ClientDataDao getArchiveClientData:self.appKey];
        NSMutableArray *tagArray = [TagDao getArchiveTag];
        NSMutableArray *activitiesArray = [UsingLogDao getArchiveActivityLog];
        
        [ClientDataDao postArchiveLogsByType:eventArray activities:activitiesArray errors:errorLogArray clientdatas:clientDataArray tags:tagArray appKey:appKey];
    }
}

#pragma mark - Clientdata processing
- (void)postClientData
{
    [self performSelectorInBackground:@selector(doPostClientDataInBackground) withObject:nil];
}

- (void)doPostClientDataInBackground
{
    @autoreleasepool {
        ClientData *clientData = [self getDeviceInfo];
        CommonReturn *ret ;
        ret = [ClientDataDao postClient:self.appKey deviceInfo:clientData];
        
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
            //            NSData *oldData = [[NSUserDefaults standardUserDefaults] objectForKey:@"clientDataArray"] ;
            NSData *oldData = [UMSAgent getArchivedLogFromFile:kClientDataArray];
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
                NSLog(@"Archived client data = %lu",(unsigned long)[mClientDataArray count]);
            }
            NSData *newData = [NSKeyedArchiver archivedDataWithRootObject:mClientDataArray];
            //            [[NSUserDefaults standardUserDefaults] setObject:newData forKey:@"clientDataArray"];
            //            [[NSUserDefaults standardUserDefaults] synchronize];
            [UMSAgent checkSizeAndSaveObject:newData ToFile:kClientDataArray];
        }
    }
}

#pragma mark - appInfo

#pragma mark - file utils
+ (NSString*) getFilePath:(NSString*)fileName{
    NSArray *array = NSSearchPathForDirectoriesInDomains(NSCachesDirectory, NSUserDomainMask, YES);
    NSString *directory = [[array objectAtIndex:0]stringByAppendingPathComponent:@"Razor_Log"];
    NSFileManager *fm = [NSFileManager defaultManager];
    if (![fm fileExistsAtPath:directory]) {
        [fm createDirectoryAtPath:directory withIntermediateDirectories:YES attributes:nil error:nil];
    }
    NSString *result = [directory stringByAppendingPathComponent:fileName];
    return result;
}

+ (long long) fileSizeAtPath:(NSString*) filePath
{
    NSFileManager *fm = [NSFileManager defaultManager];
    if ([fm fileExistsAtPath:filePath]) {
        return [[fm attributesOfItemAtPath:filePath error:nil] fileSize];
    }
    return 0;
}

+ (long long) sizeOfFile: (NSString*) fileName {
    NSFileManager *fm = [NSFileManager defaultManager];
    NSString *path = [UMSAgent getFilePath:fileName];
    if ([fm fileExistsAtPath:path]) {
        return [[fm attributesOfItemAtPath:path error:nil] fileSize];
    }
    return 0;
}

+ (void) checkSizeAndSaveObject:(id)object ToFile:(NSString*)fileName
{
    NSString *path = [self getFilePath:fileName];
    [NSKeyedArchiver archiveRootObject:object toFile:path];
    [UMSAgent fileTooLargeNeedRemoval:fileName];
}

+ (void) removeArchivedFile: (NSString*)fileName
{
    NSFileManager *fm = [NSFileManager defaultManager];
    NSString *path = [UMSAgent getFilePath:fileName];
    [fm removeItemAtPath:path error:nil];
    if([[UMSAgent getInstance] isLogEnabled]) {
        NSLog(@"Archive at %@ Just REMOVED!", path);
    }
}

+ (BOOL) fileTooLargeNeedRemoval: (NSString*)fileName {
    NSString *path = [UMSAgent getFilePath:fileName];
    BOOL tooLarge = NO;
    long long threshold = [UMSAgent getInstance].maxCacheSize * 1024 * 1024;
    if ([self fileSizeAtPath:path] > threshold) {
        if ([[UMSAgent getInstance] isLogEnabled]) {
            NSLog(@"Archive at %@  size exceeds limit: %lld, actual size=%lld", path ,threshold, [self fileSizeAtPath:path]);
        }
        [UMSAgent removeArchivedFile:fileName];
        tooLarge = YES;
    }
    return tooLarge;
}

+ (NSData*) getArchivedLogFromFile: (NSString*)fileName {
    NSData *logData = nil;
    if (![UMSAgent fileTooLargeNeedRemoval:fileName]) {
        logData = [NSKeyedUnarchiver unarchiveObjectWithFile:[UMSAgent getFilePath:fileName]];
    }
    return logData;
}


#pragma mark - online configuration & CustomParams
+ (NSString *)getConfigParam:(NSString*)key
{
    NSString *paramValue = [[NSUserDefaults standardUserDefaults] objectForKey:[NSString stringWithFormat:@"cus_%@",key]];
    if(paramValue != nil)
    {
        return paramValue;
    }
    return @"";
}

+ (void)setOnLineConfig:(BOOL)isOnlineConfig
{
    [UMSAgent getInstance].isOnLineConfig = isOnlineConfig;
    [UMSAgent getInstance].policy = [Global getConfigParams:@"reportPolicy"];
    [UMSAgent getInstance].updateOnlyWifi = [Global getConfigParams:@"updateOnlyWifi"];
    [UMSAgent getInstance].sessionmillis = [Global getConfigParams:@"sessionMillis"];
    [UMSAgent getInstance].sendInterval = [Global getConfigParams:@"intervalTime"];
    [UMSAgent getInstance].maxCacheSize = [Global getConfigParams:@"fileSize"];
}

- (void)updateOnlineConfig
{
    [self performSelectorInBackground:@selector(updateOnlineConfigInBackground) withObject:nil];
}

- (void)updateOnlineConfigInBackground
{
    if ([UMSAgent getInstance].isOnLineConfig) {
        ConfigPreference *config ;
        config = [self doUpdateOnlineConfig];
        [UMSAgent getInstance].sessionmillis = config.sessionmillis;
        [UMSAgent getInstance].updateOnlyWifi = config.Updateonlywifi;
        [UMSAgent getInstance].policy = config.reportpolicy;     ///
        [UMSAgent getInstance].sendInterval = config.sendInterval;
    }
    else {
        NSLog(@"Not Online");
    }
}

+ (void)updateOnlineParams
{
    [[UMSAgent getInstance] updateOnlineConfig];
    [[UMSAgent getInstance] updateCustomParams];
}

- (ConfigPreference *)doUpdateOnlineConfig
{
    ConfigPreference * ret ;
    ret = [ConfigDao getOnlineConfig:self.appKey];
    if (ret.flag>0) {
        if(isLogEnabled)
        {
            NSLog(@"Update Online Config Success");
        }
    }
    return ret;
}

- (void)updateCustomParams
{
    [self performSelectorInBackground:@selector(updateCustomParamsInBackground) withObject:nil];
}

- (void)updateCustomParamsInBackground
{
    [ConfigDao getCustomParams:self.appKey];
}

- (void)alertView:(UIAlertView *)alertView didDismissWithButtonIndex:(NSInteger)buttonIndex {
    if(buttonIndex == 1)
    {
        [[UIApplication sharedApplication] openURL:[NSURL URLWithString:updateRet.fileurl]];
    }
}


//Exception catching before app crash
void uncaughtExceptionHandler(NSException *exception) {
    if([UMSAgent getInstance].isLogEnabled)
    {
        NSLog(@"CRASH: %@", exception);
        NSLog(@"Stack Trace: %@", [exception callStackSymbols]);
    }
    NSString *stackTrace = [[NSString alloc] initWithFormat:@"%@\n%@",exception,[exception callStackSymbols]];
    [UMSAgent saveErrorLog:stackTrace];
}


+ (void)setPostIntervalMillis:(int)interval
{
    [UMSAgent getInstance].sendInterval = interval;
}

#pragma mark - post interval
//Timer fired method when policy is INTERVAL
- (void)postTimerFired:(NSTimer *)timer{
    [self postDataInterval];
}

- (void)postDataInterval
{
    if([UMSAgent getInstance].isLogEnabled)
    {
        NSLog(@"Send interval = %d",[UMSAgent getInstance].sendInterval);
    }
    
    //Check if time reach  sendInterval.
    NSTimeInterval intervalSinceLastFire = -[postTimerFireDate timeIntervalSinceNow];
    if(intervalSinceLastFire + 0.00000001 > [UMSAgent getInstance].sendInterval*60)
    {
        postTimerFireDate = [[NSDate date] copy];
        [self postCacheDataWithoutUsingLogs];
    }
}

- (void)postCacheDataWithoutUsingLogs
{
    [self performSelectorInBackground:@selector(processArchivedLogsWithoutUsingLogs) withObject:nil];
}

- (void) processArchivedLogsWithoutUsingLogs
{
    @autoreleasepool {
        NSMutableArray *eventArray = [EventDao getArchiveEvent:appKey];
        NSMutableArray *errorLogArray = [ErrorDao getArchiveErrorLog];
        NSMutableArray *clientDataArray = [ClientDataDao getArchiveClientData:self.appKey];
        NSMutableArray *tagArray = [TagDao getArchiveTag];
        //NSMutableArray *activitArray = []
        
        [ClientDataDao postArchiveLogsByType:eventArray activities:nil errors:errorLogArray clientdatas:clientDataArray tags:tagArray appKey:appKey];
        
    }
}

#pragma mark - collect info & utils
- (NSString*)generateSessionId
{
    NSString *currentTime = [[NSString alloc] initWithFormat:@"%f",[[NSDate date] timeIntervalSince1970]];
    NSString *sessionIdentifier = [[NSString alloc] initWithFormat:@"%@%@", currentTime, [UMSAgent getUMSUDID]];
    return [self md5:sessionIdentifier];
}

+ (NSString*)getSessionId
{
    return [UMSAgent getInstance].sessionId;
}

- (NSString *)md5:(NSString *)str {
    const char *cStr = [str UTF8String];
    unsigned char result[32];
    CC_MD5( cStr, (CC_LONG)strlen(cStr), result );
    return [NSString stringWithFormat:
            @"%02X%02X%02X%02X%02X%02X%02X%02X%02X%02X%02X%02X%02X%02X%02X%02X",
            result[0], result[1], result[2], result[3],
            result[4], result[5], result[6], result[7],
            result[8], result[9], result[10], result[11],
            result[12], result[13], result[14], result[15]
            ];
}

+ (void)bindUserIdentifier:(NSString *)userid
{
    [[NSUserDefaults standardUserDefaults] setObject:userid forKey:@"useridentifier"];
    [[NSUserDefaults standardUserDefaults] synchronize];
}

+ (NSString*)getUserId
{
    NSString *userId = [[NSUserDefaults standardUserDefaults] objectForKey:@"useridentifier"];
    if(userId!=nil)
    {
        return userId;
    }
    return @"";
}

- (NSString *) getVersion
{
    NSString *appVersion = [[[NSBundle mainBundle] infoDictionary] objectForKey:@"CFBundleShortVersionString"];
    return appVersion;
}

- (ClientData *)getDeviceInfo
{
    ClientData  *info = [[ClientData alloc] init];
    info.platform = [[UIDevice currentDevice] systemName];
    info.devicename = [self machineName];
    info.modulename = [[UIDevice currentDevice] model];
    info.os_version = [[UIDevice currentDevice] systemVersion];
    info.time = [self getCurrentTime];
    info.sessionId = self.sessionId;
    info.latitude = [self getLatitude];
    info.longitude = [self getLongitude];
    info.lib_version = LIB_VERSION;
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
    
    NSString *userid = [[NSUserDefaults standardUserDefaults] objectForKey:@"useridentifier"];
    if (userid==nil) {
        userid = @"";
    }
    
    info.useridentifier = userid;
    
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

- (NSString *)getCurrentTime
{
    NSDateFormatter *dateFormatter = [[NSDateFormatter alloc] init];
    dateFormatter.dateFormat = @"yyyy-MM-dd HH:mm:ss";
    NSTimeZone *gmt = [NSTimeZone timeZoneWithAbbreviation:@"ABC"];
    [dateFormatter setTimeZone:gmt];
    NSString *timeStamp = [dateFormatter stringFromDate:[NSDate date]];
    return timeStamp;
    
}

- (NSString *)getDateStr:(NSDate *)inputDate
{
    NSDateFormatter *dateFormatter = [[NSDateFormatter alloc] init];
    dateFormatter.dateFormat = @"yyyy-MM-dd HH:mm:ss";
    NSTimeZone *gmt = [NSTimeZone timeZoneWithAbbreviation:@"ABC"];
    [dateFormatter setTimeZone:gmt];
    NSString *timeStamp = [dateFormatter stringFromDate:inputDate];
    return timeStamp;
    
}



+ (BOOL)isJailbroken
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



- (BOOL)isWiFiAvailable
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

- (void)dealloc
{
    NSSetUncaughtExceptionHandler(NULL);
}

- (NSString*) machineName
{
    struct utsname systemInfo;
    uname(&systemInfo);
    return  [NSString stringWithCString:systemInfo.machine
                               encoding:NSUTF8StringEncoding];
}

static NSUUID *ExecutableUUID(void)
{
    const struct mach_header *executableHeader = NULL;
    for (uint32_t i = 0; i < _dyld_image_count(); i++)
    {
        const struct mach_header *header = _dyld_get_image_header(i);
        if (header->filetype == MH_EXECUTE)
        {
            executableHeader = header;
            break;
        }
    }
    
    if (!executableHeader)
        return nil;
    
    BOOL is64bit = executableHeader->magic == MH_MAGIC_64 || executableHeader->magic == MH_CIGAM_64;
    uintptr_t cursor = (uintptr_t)executableHeader + (is64bit ? sizeof(struct mach_header_64) : sizeof(struct mach_header));
    const struct segment_command *segmentCommand = NULL;
    for (uint32_t i = 0; i < executableHeader->ncmds; i++, cursor += segmentCommand->cmdsize)
    {
        segmentCommand = (struct segment_command *)cursor;
        if (segmentCommand->cmd == LC_UUID)
        {
            const struct uuid_command *uuidCommand = (const struct uuid_command *)segmentCommand;
            return [[NSUUID alloc] initWithUUIDBytes:uuidCommand->uuid];
        }
    }
    
    return nil;
}

NSString *getCPUType(void)
{
    NSMutableString *cpu = [[NSMutableString alloc] init];
    size_t size;
    cpu_type_t type;
    cpu_subtype_t subtype;
    size = sizeof(type);
    sysctlbyname("hw.cputype", &type, &size, NULL, 0);
    
    size = sizeof(subtype);
    sysctlbyname("hw.cpusubtype", &subtype, &size, NULL, 0);
    
    // values for cputype and cpusubtype defined in mach/machine.h
    if (type == CPU_TYPE_X86)
    {
        [cpu appendString:@"x86 "];
        // check for subtype ...
        
    } else if (type == CPU_TYPE_ARM)
    {
        [cpu appendString:@"ARM"];
        switch(subtype)
        {
            case CPU_SUBTYPE_ARM_V7:
                [cpu appendString:@"V7"];
                break;
            case CPU_SUBTYPE_ARM_V7S:
                [cpu appendString:@"V7S"];
            default:
                break;
        }
    }else if (type == CPU_TYPE_ARM64){
        [cpu appendString:@"ARM"];
        switch (subtype) {
            case CPU_SUBTYPE_ARM64_V8:
                [cpu appendString:@"64"];
                break;
                
            default:
                break;
        }
    }
    return cpu;
}

+ (NSString *)getUMSUDID
{
    NSString * udidInKeyChain = [SFHFKeychainUtils getPasswordForUsername:@"UMSAgentUDID" andServiceName:@"UMSAgent" error:nil];
    if(udidInKeyChain && ![udidInKeyChain isEqualToString:@""])
    {
        return udidInKeyChain;
    }
    else
    {
        NSString *sysVersion = [[UIDevice currentDevice]systemVersion];
        NSString *firstLetter = [sysVersion substringWithRange:NSMakeRange(0, 1)];
        int versionNumber = [firstLetter intValue];
        if (versionNumber < 6) {
            NSString *macAddress =  [[UIDevice currentDevice]macAddress];
            [SFHFKeychainUtils storeUsername:@"UMSAgentUDID" andPassword:macAddress forServiceName:@"UMSAgent" updateExisting:NO error:nil];
            return macAddress;
        } else {
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
}

+ (void)setDeviceID: (NSString*)deviceID {
    [SFHFKeychainUtils storeUsername:@"UMSAgentUDID" andPassword:deviceID forServiceName:@"UMSAgent" updateExisting:YES error:nil];
}


+ (void)setGPSLocation:(double)latitude longitude:(double)longitude
{
    NSString *latitudeStr = [NSString stringWithFormat:@"%f",latitude];
    NSString *longitudeStr = [NSString stringWithFormat:@"%f",longitude];
    [[NSUserDefaults standardUserDefaults] setObject:latitudeStr forKey:@"latitude"];
    [[NSUserDefaults standardUserDefaults] setObject:longitudeStr forKey:@"longitude"];
    [[NSUserDefaults standardUserDefaults] synchronize];
}

- (NSString*)getLatitude
{
    NSString *latitudeStr = [[NSUserDefaults standardUserDefaults] objectForKey:@"latitude"];
    if(latitudeStr)
    {
        return latitudeStr;
    }
    else
    {
        return @"";
    }
}

- (NSString*)getLongitude
{
    NSString *longitudeStr = [[NSUserDefaults standardUserDefaults] objectForKey:@"longitude"];
    if(longitudeStr)
    {
        return longitudeStr;
    }
    else
    {
        return @"";
    }
}

//Enable log print for developer
+ (void)setIsLogEnabled:(BOOL)isLogEnabled
{
    [UMSAgent getInstance].isLogEnabled = isLogEnabled;
}

//Auto update only under WIFI
+ (void)setUpdateOnlyWifi:(BOOL)isUnderWIFI
{
    if(isUnderWIFI)
    {
        [UMSAgent getInstance].updateOnlyWifi = 1;
    }
    else
    {
        [UMSAgent getInstance].updateOnlyWifi = 0;
    }
}

#pragma mark - Application update
+ (void)checkUpdate
{
    if(![UMSAgent getInstance].updateOnlyWifi)
    {
        [[UMSAgent getInstance] getApplicationUpdate];
    }
    else if ([UMSAgent getInstance].updateOnlyWifi && [[UMSAgent getInstance] isWiFiAvailable])
    {
        [[UMSAgent getInstance] getApplicationUpdate];
    }
}

- (void)getApplicationUpdate
{
    [self performSelectorInBackground:@selector(doApplicationUpdateInBackground) withObject:nil];
}

- (void)doApplicationUpdateInBackground
{
    CheckUpdateReturn *retWrapper;
    if(isLogEnabled)
    {
        NSLog(@"Get application update");
    }
    retWrapper = [CheckUpdateDao checkUpdate:appKey version:[self getVersion] lib_version:LIB_VERSION];
    [self performSelectorOnMainThread:@selector(notifyAppUpdate:) withObject:retWrapper waitUntilDone:NO];
}

- (void)notifyAppUpdate:(CheckUpdateReturn *)retWrapper
{
    if (retWrapper.flag>0)
    {
        updateRet = retWrapper;
        NSString *version = [[NSString alloc] initWithFormat:@"有新的版本 %@",retWrapper.version];
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

@end
