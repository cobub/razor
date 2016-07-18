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
    int autogetlocation;
    int Updateonlywifi;
    int sessionmillis;
    int reportpolicy;
    int sendInterval;
    int maxFileSize;
}
@property(nonatomic) int autogetlocation;
@property(nonatomic) int Updateonlywifi;
@property(nonatomic) int sessionmillis;
@property(nonatomic) int reportpolicy;
@property(nonatomic) int sendInterval;
@property(nonatomic) int maxFileSize;
@end
