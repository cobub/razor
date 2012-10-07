//
//  ActivityLog.m
//  UMSAgent
//
//  Created by  on 12-4-5.
//  Copyright (c) 2012年 __MyCompanyName__. All rights reserved.
//

#import "ActivityLog.h"

@implementation ActivityLog
@synthesize sessionMils;
@synthesize startMils;
@synthesize endMils;
@synthesize duration;
@synthesize activity;
@synthesize version;

-(id)initWithCoder:(NSCoder *)aDecoder
{
    if (self =[super init]) {
        self.sessionMils = [aDecoder decodeObjectForKey:@"sessionMils"];
        self.startMils = [aDecoder decodeObjectForKey:@"startMils"];
        self.endMils = [aDecoder decodeObjectForKey:@"endMils"];
        self.duration = [aDecoder decodeObjectForKey:@"duration"];
        self.activity = [aDecoder decodeObjectForKey:@"activity"];
        self.version = [aDecoder decodeObjectForKey:@"version"];
    }
    return self;
}

-(void)encodeWithCoder:(NSCoder *)aCoder
{
    [aCoder encodeObject:sessionMils forKey:@"sessionMils"];
    [aCoder encodeObject:startMils forKey:@"startMils"];
    [aCoder encodeObject:endMils forKey:@"endMils"];
    [aCoder encodeObject:duration forKey:@"duration"];
    [aCoder encodeObject:activity forKey:@"activity"];
    [aCoder encodeObject:version forKey:@"version"];
}


@end
