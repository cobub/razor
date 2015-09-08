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
