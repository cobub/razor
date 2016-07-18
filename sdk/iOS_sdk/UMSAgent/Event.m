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

#import "Event.h"

@implementation Event
@synthesize event_id,time,acc,activity,label,version,jsonstr,lib_version;

- (id)initWithCoder:(NSCoder *)aDecoder
{
    if (self =[super init]) {
        self.event_id = [aDecoder decodeObjectForKey:@"event_id"];
        self.label = [aDecoder decodeObjectForKey:@"label"];
        self.time = [aDecoder decodeObjectForKey:@"time"];
        self.activity = [aDecoder decodeObjectForKey:@"activity"];
        self.acc = [aDecoder decodeInt32ForKey:@"acc"];
        self.version = [aDecoder decodeObjectForKey:@"version"];
        self.jsonstr = [aDecoder decodeObjectForKey:@"jsonstr"];
        self.lib_version = [aDecoder decodeObjectForKey:@"lib_version"];
    }
    return self;
}

- (void)encodeWithCoder:(NSCoder *)aCoder
{
    
    [aCoder encodeObject:event_id forKey:@"event_id"];
    [aCoder encodeObject:label forKey:@"label"];
    [aCoder encodeObject:time forKey:@"time"];
    [aCoder encodeObject:activity forKey:@"activity"];
    [aCoder encodeObject:version forKey:@"version"];
    [aCoder encodeInt:acc forKey:@"acc"];
    [aCoder encodeObject:jsonstr forKey:@"jsonstr"];
    [aCoder encodeObject:lib_version forKey:@"lib_version"];
}



@end
