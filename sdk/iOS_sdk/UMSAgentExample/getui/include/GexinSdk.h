//
//  GexinSdk.h
//  GexinSdk
//
//  Created by user on 11-12-28.
//  Copyright (c) 2011年 Gexin Interactive (Beijing) Network Technology Co.,LTD. All rights reserved.
//

#import <Foundation/Foundation.h>
#import "GXSdkError.h"


@protocol GexinSdkDelegate;

@interface GexinSdk : NSObject {
@private
    void *_impl;
}

+ (NSString *)version;

+ (void)setAllowedRotateUiOrientations:(NSArray *)orientations;

+ (GexinSdk *)createSdkWithAppId:(NSString *)appid 
                         appKey:(NSString *)appKey 
                      appSecret:(NSString *)appSecret 
                      appVersion:(NSString *)aAppVersion
                       delegate:(id<GexinSdkDelegate>)delegate
                          error:(NSError **)error;

- (NSData *)retrivePayloadById:(NSString *)payloadId;

- (void)registerDeviceToken:(NSString *)deviceToken;
- (BOOL)setTags:(NSArray *)tags;
- (NSString *)sendMessage:(NSData *)body error:(NSError **)error;
- (void)destroy;

@end

@protocol GexinSdkDelegate <NSObject>
@optional
- (void)GexinSdkDidRegisterClient:(NSString *)clientId;
- (void)GexinSdkDidReceivePayload:(NSString *)payloadId fromApplication:(NSString *)appId;
- (void)GexinSdkDidSendMessage:(NSString *)messageId result:(int)result;
- (void)GexinSdkDidOccurError:(NSError *)error;
@end
