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

#import "network.h"
#import "json/SBJson.h"
@implementation network

//-(NSString*) urlEncode:(NSString*)string
//{
//    NSString *encodedString = (NSString *)CFURLCreateStringByAddingPercentEscapes( NULL, (CFStringRef)string, NULL, (CFStringRef)@"!*'();:@&=+$,/?%#[]", kCFStringEncodingUTF8 );
//    return [encodedString autorelease];
//}`


+(NSString*)SendData:(NSString*)URLString data:(NSMutableDictionary*)content
{
    @autoreleasepool {
	NSURL *url = [NSURL URLWithString:URLString];
	NSString *str = [content JSONRepresentation];
	str = [NSString stringWithFormat:@"content=%@",str];
	NSLog(@"URL=%@;Send Data = %@",url,str);
	NSData *requestData = [str dataUsingEncoding:NSUTF8StringEncoding];
	NSMutableURLRequest * request = [[NSMutableURLRequest alloc] initWithURL:url];
	[request setHTTPMethod: @"POST"];
	[request setHTTPBody: requestData];
	
	NSError        *error = nil;
	NSURLResponse  *response = nil;
	
    NSData *returnData = [ NSURLConnection sendSynchronousRequest: request returningResponse: &response error: &error ];    
	if (response == nil) {
		if (error != nil) {
			NSLog(@"Connection failed! Error - %@ %@ - [%@]",
				  [error localizedDescription],
				  [[error userInfo] objectForKey:NSURLErrorFailingURLStringErrorKey],
                  @"error");
		}

		return nil;
	}
	else {

            NSString *jsonString = [[NSString alloc] initWithData:returnData encoding:NSUTF8StringEncoding];
            NSLog(@"RET JSON STR = %@",jsonString);		
            jsonString = [jsonString stringByReplacingPercentEscapesUsingEncoding:NSUTF8StringEncoding];
            return jsonString;
        }
    }
	
}


@end

