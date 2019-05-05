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

#import "ErrorLog.h"

@implementation ErrorLog
@synthesize activity;
@synthesize time;
@synthesize stackTrace;
@synthesize appkey;
@synthesize version;
@synthesize osVersion;
@synthesize deviceID;
@synthesize uuID;
@synthesize cpt;
@synthesize bim;
@synthesize lib_version;
@synthesize sessionID;

- (id)initWithCoder:(NSCoder *)aDecoder
{
    if (self =[super init]) {
        self.activity = [aDecoder decodeObjectForKey:@"activity"];
        self.time = [aDecoder decodeObjectForKey:@"time"];
        self.stackTrace = [aDecoder decodeObjectForKey:@"stackTrace"];
        self.appkey = [aDecoder decodeObjectForKey:@"appkey"];
        self.version = [aDecoder decodeObjectForKey:@"version"];
        self.osVersion = [aDecoder decodeObjectForKey:@"osVersion"];
        self.deviceID = [aDecoder decodeObjectForKey:@"deviceID"];
        self.uuID = [aDecoder decodeObjectForKey:@"uuID"];
        self.cpt = [aDecoder decodeObjectForKey:@"cpt"];
        self.bim = [aDecoder decodeObjectForKey:@"bim"];
        self.lib_version = [aDecoder decodeObjectForKey:@"lib_version"];
        self.sessionID = [aDecoder decodeObjectForKey:@"session_id"];

    }
    return self;
}

- (void)encodeWithCoder:(NSCoder *)aCoder
{
    [aCoder encodeObject:activity forKey:@"activity"];
    [aCoder encodeObject:time forKey:@"time"];
    [aCoder encodeObject:stackTrace forKey:@"stackTrace"];
    [aCoder encodeObject:appkey forKey:@"appkey"];
    [aCoder encodeObject:version forKey:@"version"];
    [aCoder encodeObject:osVersion forKey:@"osVersion"];
    [aCoder encodeObject:deviceID forKey:@"deviceID"];
    [aCoder encodeObject:uuID forKey:@"uuID"];
    [aCoder encodeObject:cpt forKey:@"cpt"];
    [aCoder encodeObject:bim forKey:@"bim"];
    [aCoder encodeObject:lib_version forKey:@"lib_version"];
    [aCoder encodeObject:sessionID forKey:@"session_id"];

}

@end
