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
#import "CJSONSerializer.h"

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
        NSError *error = NULL;
        NSData *requestData = [[CJSONSerializer serializer] serializeDictionary:content error:&error];
        if(error)
        {
            NSLog(@"Serialization Error: %@", error);
            return @"{\"flag\":-10,\"msg\":\"Json serializaion error.\"}";
        }
        NSString *requestStr = [[NSString alloc] initWithData:requestData encoding:NSUTF8StringEncoding];
        requestStr = [NSString stringWithFormat:@"content=%@",requestStr];
        requestData = [requestStr dataUsingEncoding:NSUTF8StringEncoding];
        
        NSMutableURLRequest * request = [[NSMutableURLRequest alloc] initWithURL:url];
        [request setHTTPMethod: @"POST"];
        [request setHTTPBody: requestData];
        
        NSError        *responseError = nil;
        NSURLResponse  *response = nil;
        
        NSData *returnData = [ NSURLConnection sendSynchronousRequest: request returningResponse: &response error: &responseError ];
        if (response == nil) {
            if (responseError != nil) {
                NSLog(@"Connection to server failed.");
                //NSLog(@"Connection failed! Error - %@ %@ - [%@]",
                //	  [error localizedDescription],
                //	  [[error userInfo] objectForKey:NSURLErrorFailingURLStringErrorKey],
                //      @"error");
            }
            
            return @"{\"flag\":-9,\"msg\":\"network connection error\"}";
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

