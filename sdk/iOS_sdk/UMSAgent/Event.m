//
//  Event.m
//  UMSAgent
//
//  Created by  on 12-3-21.
//  Copyright (c) 2012年 __MyCompanyName__. All rights reserved.
//

#import "Event.h"

@implementation Event
@synthesize event_id,time,acc,activity,label,version;

-(id)initWithCoder:(NSCoder *)aDecoder
{
    if (self =[super init]) {
        self.event_id = [aDecoder decodeObjectForKey:@"event_id"];
        self.label = [aDecoder decodeObjectForKey:@"label"];
        self.time = [aDecoder decodeObjectForKey:@"time"];
        self.activity = [aDecoder decodeObjectForKey:@"activity"];
        self.acc = [aDecoder decodeInt32ForKey:@"acc"];
        self.version = [aDecoder decodeObjectForKey:@"version"];
    }
    return self;
}

-(void)encodeWithCoder:(NSCoder *)aCoder
{

    [aCoder encodeObject:event_id forKey:@"event_id"];
    [aCoder encodeObject:label forKey:@"label"];
    [aCoder encodeObject:time forKey:@"time"];
    [aCoder encodeObject:activity forKey:@"activity"];
    [aCoder encodeObject:version forKey:@"version"];
    [aCoder encodeInt:acc forKey:@"acc"];
}



@end
