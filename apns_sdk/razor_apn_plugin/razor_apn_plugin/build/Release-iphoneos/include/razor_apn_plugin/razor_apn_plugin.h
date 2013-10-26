//
//  razor_apn_plugin.h
//  razor_apn_plugin
//
//  Created by guowei on 13-9-4.
//  Copyright (c) 2013年 WBTECH. All rights reserved.
//

#import <Foundation/Foundation.h>

@interface razor_apn_plugin : NSObject

+(BOOL)resisterDevice:(NSString*)deviceId token:(NSString*)deviceToken;

@end
