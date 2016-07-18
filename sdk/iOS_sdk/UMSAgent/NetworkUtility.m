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

#import "NetworkUtility.h"
#import "CJSONSerializer.h"
#import "UMSAgent.h"

@implementation NetworkUtility

+ (NSString*)postData:(NSString*)URLString data:(NSMutableDictionary*)content
{
    @autoreleasepool {
        NSURL *url = [NSURL URLWithString:URLString];
        NSLog(@"url:%@",url.absoluteString);
        
        NSError *error = NULL;
        NSData *requestData = [[CJSONSerializer serializer] serializeDictionary:content error:&error];
        if(error)
        {
            if([UMSAgent getInstance].isLogEnabled)
            {
                NSLog(@"Serialization Error: %@", error);
            }
            return @"{\"flag\":-10,\"msg\":\"Json serializaion error.\"}";
        }
        NSString *requestStr = [[NSString alloc] initWithData:requestData encoding:NSUTF8StringEncoding];
        //Encoding
        NSString *encodedString = [requestStr stringByAddingPercentEscapesUsingEncoding:NSUTF8StringEncoding];
        if([UMSAgent getInstance].isLogEnabled)
        {
            NSLog(@"%@", requestStr);
        }
        
        requestStr = [NSString stringWithFormat:@"content=%@",encodedString];
        
        
        requestData = [requestStr dataUsingEncoding:    NSUTF8StringEncoding];
        
        NSMutableURLRequest * request = [[NSMutableURLRequest alloc] initWithURL:url];
        //[request setValue:@"gzip" forHTTPHeaderField:@"Accept-Encoding"];
        [request setHTTPMethod: @"POST"];
        [request setHTTPBody: requestData];
        
        NSError        *responseError = nil;
        NSURLResponse  *response = nil;
        
        NSData *returnData = [ NSURLConnection sendSynchronousRequest: request returningResponse: &response error: &responseError ];
        if (response == nil) {
            if (responseError != nil) {
                if([UMSAgent getInstance].isLogEnabled)
                {
                    NSLog(@"Connection to server failed.");
                }
            }
            return @"{\"flag\":-9,\"msg\":\"network connection error\"}";
        }
        else
        {
            NSString *jsonString = [[NSString alloc] initWithData:returnData encoding:NSUTF8StringEncoding];
            if([UMSAgent getInstance].isLogEnabled)
            {
                NSLog(@"RET JSON STR = %@",jsonString);
            }
            jsonString = [jsonString stringByReplacingPercentEscapesUsingEncoding:NSUTF8StringEncoding];
            jsonString = [jsonString stringByTrimmingCharactersInSet: [NSCharacterSet whitespaceAndNewlineCharacterSet]];
            return jsonString;
        }
    }
    
}


@end

