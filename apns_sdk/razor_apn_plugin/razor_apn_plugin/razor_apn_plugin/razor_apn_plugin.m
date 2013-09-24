//
//  razor_apn_plugin.m
//  razor_apn_plugin
//
//  Created by guowei on 13-9-4.
//  Copyright (c) 2013年 WBTECH. All rights reserved.
//

#import "razor_apn_plugin.h"
#import "razor_apn_network.h"
#import <Foundation/Foundation.h>

@implementation razor_apn_plugin

+(BOOL)resisterDevice:(NSString*)deviceId token:(NSString*)deviceToken appId:(NSString *)appId
{
    NSLog(@"DeviceID = %@",deviceId);
    NSLog(@"Token = %@",deviceToken);
    NSLog(@"AppId = %@",appId);
//    NSString *data = [NSString stringWithFormat:@"\r\n--deviceid=%@\r\n--device_token=%@;app_id=%@;check_sum=%@",deviceId,deviceToken,appId,[razor_apn_plugin getEncryptKey:deviceId token:deviceToken appId:appId]];
//    NSLog(@"data = %@",data);
    NSDictionary *postParams = [NSDictionary dictionaryWithObjectsAndKeys:deviceId,@"deviceid",deviceToken,@"device_token",appId,@"app_id",[razor_apn_plugin getEncryptKey:deviceId token:deviceToken appId:appId],@"check_sum", nil];
    if ([NSJSONSerialization isValidJSONObject:postParams]) {
        NSError *error;
        NSData *postData = [NSJSONSerialization dataWithJSONObject:postParams options:NSJSONWritingPrettyPrinted error:&error];
        NSString *postStr = [[NSString alloc] initWithData:postData encoding:NSUTF8StringEncoding];
        NSLog(@"Post STr = %@",postStr);
        [razor_apn_network postData:@"http://192.168.1.104:8877/ucenter/index.php?/api/apns/mapping" data:postStr];
    }

    
    return YES;
}

+(NSString*)getEncryptKey:(NSString*)deviceId token:(NSString*)deviceToken appId:(NSString*)appId
{
    NSString *p1 = [razor_apn_network md5:[NSString stringWithFormat:@"%@%@",deviceId,deviceToken]];
    NSLog(@"p1 = %@",p1);
    NSString *p2 = [razor_apn_network md5:[NSString stringWithFormat:@"%@%@",deviceId,appId]];
    NSString *p3 = [razor_apn_network md5:[NSString stringWithFormat:@"%@%@",deviceToken,appId]];
    return [razor_apn_network md5:[NSString stringWithFormat:@"%@%@%@",p1,p2,p3]];
}

@end
