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
#include "CommonReturn.h"

@interface ConfigPreference : CommonReturn
{
    NSString *autogetlocation;
    NSString *Updateonlywifi;
    NSString *sessionmillis;
    NSString *reportpolicy;
}
@property(strong,nonatomic) NSString *autogetlocation;
@property(strong,nonatomic) NSString *Updateonlywifi;
@property(strong,nonatomic) NSString *sessionmillis;
@property(strong,nonatomic) NSString *reportpolicy;
@end
