//
//  AppInfo.m
//  UMSAgent
//
//  Created by FEIYue on 5/4/15.
//
//

#import "AppInfo.h"

@implementation AppInfo

- (id)initWithCoder:(NSCoder *)aDecoder{
    if (self =[super init]) {
        self.appKey = [aDecoder decodeObjectForKey:kAppKey];
        self.appVersion = [aDecoder decodeObjectForKey:kVersion];
        self.userIdentifier = [aDecoder decodeObjectForKey:kUserId];
        self.appName = [aDecoder decodeObjectForKey:kAppName];
        self.deviceid = [aDecoder decodeObjectForKey:kDeviceId];
        self.lib_version = [aDecoder decodeObjectForKey:@"lib_version"];
    }
    return self;
}

- (void)encodeWithCoder:(NSCoder *)aCoder{
    [aCoder encodeObject:self.appKey forKey:kAppKey];
    [aCoder encodeObject:self.appVersion forKey:kVersion];
    [aCoder encodeObject:self.userIdentifier forKey:kUserId];
    [aCoder encodeObject:self.appName forKey:kAppName];
    [aCoder encodeObject:self.deviceid forKey:kDeviceId];
    [aCoder encodeObject:self.lib_version forKey:@"lib_version"];
}
@end
