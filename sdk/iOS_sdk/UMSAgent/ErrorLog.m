//
//  ErrorLog.m
//  UMSAgent
//
//  Created by  on 12-5-16.
//  Copyright (c) 2012年 __MyCompanyName__. All rights reserved.
//

#import "ErrorLog.h"

@implementation ErrorLog
@synthesize activity;
@synthesize time;
@synthesize stackTrace;
@synthesize appkey;
@synthesize version;
@synthesize osVersion;
@synthesize deviceID;

-(id)initWithCoder:(NSCoder *)aDecoder
{
    if (self =[super init]) {
        self.activity = [aDecoder decodeObjectForKey:@"activity"];
        self.time = [aDecoder decodeObjectForKey:@"time"];
        self.stackTrace = [aDecoder decodeObjectForKey:@"stackTrace"];
        self.appkey = [aDecoder decodeObjectForKey:@"appkey"];
        self.version = [aDecoder decodeObjectForKey:@"version"];
        self.osVersion = [aDecoder decodeObjectForKey:@"osVersion"];
        self.deviceID = [aDecoder decodeObjectForKey:@"deviceID"];
    }
    return self;
}

-(void)encodeWithCoder:(NSCoder *)aCoder
{
    [aCoder encodeObject:activity forKey:@"activity"];
    [aCoder encodeObject:time forKey:@"time"];
    [aCoder encodeObject:stackTrace forKey:@"stackTrace"];
    [aCoder encodeObject:appkey forKey:@"appkey"];
    [aCoder encodeObject:version forKey:@"version"];
    [aCoder encodeObject:osVersion forKey:@"osVersion"];
    [aCoder encodeObject:deviceID forKey:@"deviceID"];
}

@end
