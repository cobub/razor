//
//  razor_apn_network.h
//  razor_apn_plugin
//
//  Created by guowei on 13-9-6.
//  Copyright (c) 2013年 WBTECH. All rights reserved.
//

#import <Foundation/Foundation.h>

@interface razor_apn_network : NSObject

+(BOOL)postData:(NSString*)url data:(NSString*)data;
+(NSString *)md5:(NSString *)str;
@end
