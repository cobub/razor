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

@interface Global : NSObject
{
    
}
+(void)ShowAlertView:(NSString*)title message:(NSString*)message delegate:(id)delegate buttonTitle:(NSString*)buttonTitle cancelButtonTitle:(NSString*)title;
+(void)setBaseURL:(NSString *)baseURL;
+(NSString*)getBaseURL;
@end
