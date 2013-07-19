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
#import "Global.h"
#import <UIKit/UIKit.h>
@implementation Global

static NSString *BASEURL;

+(void)ShowAlertView:(NSString*)title message:(NSString*)message delegate:(id)delegate buttonTitle:(NSString*)buttonTitle cancelButtonTitle:(NSString*)cancelTitle
{
    UIAlertView *alert = [[UIAlertView alloc] initWithTitle: title
                                                    message: message
                                                   delegate: delegate
                                          cancelButtonTitle:title
                                          otherButtonTitles:buttonTitle, nil];
    [alert dismissWithClickedButtonIndex:0 animated:YES];
    [alert show];
   
}

+(void)setBaseURL:(NSString *)baseURL
{
    BASEURL = [[NSString alloc] initWithString:baseURL];
    NSLog(baseURL);
}

+(NSString *)getBaseURL
{
    //NSLog(BASEURL);
    return BASEURL;
}

@end
