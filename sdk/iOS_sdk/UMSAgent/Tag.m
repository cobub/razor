//
//  Tag.m
//  UMSAgent
//
//  Created by admin on 13-4-27.
//
//

#import "Tag.h"

@implementation Tag
@synthesize deviceid;
@synthesize appkey;
@synthesize tags;
-(id)initWithCoder:(NSCoder *)aDecoder
{
    if (self =[super init]) {
        self.deviceid = [aDecoder decodeObjectForKey:@"deviceid"];
        self.appkey = [aDecoder decodeObjectForKey:@"appkey"];
        self.tags = [aDecoder decodeObjectForKey:@"tags"];    }
    return self;
}

-(void)encodeWithCoder:(NSCoder *)aCoder
{
    [aCoder encodeObject:deviceid forKey:@"deviceid"];    
    [aCoder encodeObject:appkey forKey:@"appkey"];
    [aCoder encodeObject:tags forKey:@"tags"];
}

@end
