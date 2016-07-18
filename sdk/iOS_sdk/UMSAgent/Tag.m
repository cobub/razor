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
@synthesize tag;
@synthesize lib_version;
@synthesize useridentifier;

- (id)initWithCoder:(NSCoder *)aDecoder
{
    if (self =[super init]) {
        self.deviceid = [aDecoder decodeObjectForKey:@"deviceid"];
        self.productkey = [aDecoder decodeObjectForKey:@"productkey"];
        self.tag = [aDecoder decodeObjectForKey:@"tag"];
        self.lib_version = [aDecoder decodeObjectForKey:@"lib_version"];
        self.useridentifier = [aDecoder decodeObjectForKey:@"useridentifier"];
    }
    return self;
}

- (void)encodeWithCoder:(NSCoder *)aCoder
{
    [aCoder encodeObject:deviceid forKey:@"deviceid"];
    [aCoder encodeObject:productkey forKey:@"productkey"];
    [aCoder encodeObject:tag forKey:@"tag"];
    [aCoder encodeObject:lib_version forKey:@"lib_version"];
    [aCoder encodeObject:useridentifier forKey:@"useridentifier"];
}

@end
