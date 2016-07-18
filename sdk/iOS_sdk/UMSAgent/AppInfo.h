//
//  AppInfo.h
//  UMSAgent
//
//  Created by FEIYue on 5/4/15.
//
//

#import <Foundation/Foundation.h>
#define kAppKey @"appkey"
#define kVersion @"appVersion"
#define kUserId @"userIdentifier"
#define kAppName @"appName"
#define kDeviceId @"deviceId"

@interface AppInfo : NSObject<NSCoding>
@property (nonatomic,strong) NSString *appKey;
@property (nonatomic,strong) NSString *appVersion;
@property (nonatomic,strong) NSString *userIdentifier;
@property (nonatomic,strong) NSString *appName;
@property (nonatomic,strong) NSString *deviceid;
@property (nonatomic,strong) NSString *lib_version;
@end
