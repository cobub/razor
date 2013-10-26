//
//  razor_apn_network.m
//  razor_apn_plugin
//
//  Created by guowei on 13-9-6.
//  Copyright (c) 2013年 WBTECH. All rights reserved.
//

#import "razor_apn_network.h"
#import <Foundation/Foundation.h>

@implementation razor_apn_network

+(BOOL)postData:(NSString*)url data:(NSString*)data
{
    NSURL *urlStr = [NSURL URLWithString:url];
    NSData *requestData = [data dataUsingEncoding:NSUTF8StringEncoding];
	NSMutableURLRequest * request = [[NSMutableURLRequest alloc] initWithURL:urlStr];
	[request setHTTPMethod: @"POST"];
	[request setHTTPBody: requestData];
	
	NSError        *error = nil;
	NSURLResponse  *response = nil;
    NSData *returnData = [ NSURLConnection sendSynchronousRequest: request returningResponse: &response error: &error ];
	if (response == nil)
    {
		if (error != nil) {
            NSLog(@"Connection to server failed.");
      	}
        return NO;
    }
	else {
        NSLog(@"Return = %@",[[NSString alloc] initWithData:returnData encoding:NSUTF8StringEncoding]);
        NSError        *jsonError = nil;
        NSDictionary *retJSONObj = [NSJSONSerialization JSONObjectWithData:returnData options:kNilOptions error:&jsonError];
        if(jsonError)
        {
             NSLog(@"Json parse error.");
             return NO;
        }
        else
        {
            int flag = [[retJSONObj objectForKey:@"flag"] intValue];
            if(flag>0)
            {
                return YES;
            }
            else
            {
                return NO;
            }
        }
        
    }
    return NO;
}

+(NSString *)md5:(NSString *)str {
    const char *cStr = [str UTF8String];
    unsigned char result[32];
    CC_MD5( cStr, strlen(cStr), result );
    return [NSString stringWithFormat:
            @"%02x%02x%02x%02x%02x%02x%02x%02x%02x%02x%02x%02x%02x%02x%02x%02x",
            result[0], result[1], result[2], result[3],
            result[4], result[5], result[6], result[7],
            result[8], result[9], result[10], result[11],
            result[12], result[13], result[14], result[15]
            ];
}
@end
