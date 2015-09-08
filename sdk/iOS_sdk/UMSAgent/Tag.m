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
@synthesize productkey;
@synthesize tags;
-(id)initWithCoder:(NSCoder *)aDecoder
{
    if (self =[super init]) {
        self.deviceid = [aDecoder decodeObjectForKey:@"deviceid"];
        self.productkey = [aDecoder decodeObjectForKey:@"productkey"];
        self.tags = [aDecoder decodeObjectForKey:@"tags"];    }
    return self;
}

-(void)encodeWithCoder:(NSCoder *)aCoder
{
    [aCoder encodeObject:deviceid forKey:@"deviceid"];    
    [aCoder encodeObject:productkey forKey:@"productkey"];
    [aCoder encodeObject:tags forKey:@"tags"];
}

@end
