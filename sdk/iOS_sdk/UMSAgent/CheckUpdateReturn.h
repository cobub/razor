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

#import <Foundation/Foundation.h>
#import "CommonReturn.h"
@interface CheckUpdateReturn : CommonReturn
{
    NSString *description;
    NSString *time;
    NSString *fileurl;
    NSString *forceUpdate;
    NSString *version;
    
}
@property(nonatomic,strong) NSString *description;
@property(nonatomic,strong) NSString *time;
@property(nonatomic,strong) NSString *fileurl;
@property(nonatomic,strong) NSString *forceUpdate;
@property(nonatomic,strong) NSString *version;

@end
