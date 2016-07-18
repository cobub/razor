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
#import "UMSAgent.h"

@implementation Global

static NSString *BASEURL;

+ (void)ShowAlertView:(NSString*)title message:(NSString*)message delegate:(id)delegate buttonTitle:(NSString*)buttonTitle cancelButtonTitle:(NSString*)cancelTitle
{
    UIAlertView *alert = [[UIAlertView alloc] initWithTitle: title
                                                    message: message
                                                   delegate: delegate
                                          cancelButtonTitle:title
                                          otherButtonTitles:buttonTitle, nil];
    [alert dismissWithClickedButtonIndex:0 animated:YES];
    [alert show];
    
}


+ (void)setBaseURL:(NSString *)baseURL
{
    BASEURL = [[NSString alloc] initWithString:baseURL];
}

+ (NSString *)getBaseURL
{
    return BASEURL;
}

+ (NSString *)getConfigBaseUrl
{
    return BASEURL;
}


+ (void)setConfigParams:(NSString*)value key:(NSString*)key
{
    [[NSUserDefaults standardUserDefaults] setObject:value forKey:key];
    [[NSUserDefaults standardUserDefaults] synchronize];
}

+ (int)getConfigParams:(NSString*)key
{
    id value = [[NSUserDefaults standardUserDefaults] objectForKey:key];
    if(value)
    {
        return [value intValue];
    }
    else
    {
        if([key isEqualToString:@"autoGetLocation"])
        {
            return 0;//Not used in IOS
        }
        else if([key isEqualToString:@"reportPolicy"])
        {
            return REALTIME;
        }
        else if([key isEqualToString:@"updateOnlyWifi"])
        {
            return DEFAULT_UPDATE_ONLY_WIFI;
        }
        else if([key isEqualToString:@"sessionMillis"])
        {
            return DEFAULT_SESSIONMILLIS;
        }
        else if([key isEqualToString:@"intervalTime"])
        {
            return DEFAULT_INTERVAL_TIME;
        }
        else if([key isEqualToString:@"fileSize"])
        {
            return DEFAULT_FILE_SIZE                    ;
        }
    }
    return 0;
}

@end
