//
//  UMSAgent.h
//  UMSAgent
//
//  Created by  on 12-3-16.
//  Copyright (c) 2012年 __MyCompanyName__. All rights reserved.
//

#import <Foundation/Foundation.h>
#import <UIKit/UIKit.h>

typedef enum {
    REALTIME = 0,       //实时发送
    BATCH = 1,          //启动发送
} ReportPolicy;

@protocol UMSAgentDelegate <NSObject>
@required

- (NSString *)UMSAgentKey;
@end



@interface UMSAgent : NSObject<UIAlertViewDelegate>
{
    id<UMSAgentDelegate> delegate;
}

@property(strong,nonatomic) id<UMSAgentDelegate> delegate;

+(void)checkUpdate;
+(void)setDelegate:(id<UMSAgentDelegate>)dele reportPolicy:(ReportPolicy)policy;
+(void)postClientData;
+(void)postEvent:(NSString *)event_id;
+(void)postEvent:(NSString *)event_id label:(NSString *)label;
+(void)postEvent:(NSString *)event_id acc:(NSInteger)acc;
+(void)postEvent:(NSString *)event_id label:(NSString *)label acc:(NSInteger)acc;

// 类方法，判断当前设备是否已经越狱
+ (BOOL)isJailbroken;
+ (void)setOnLineConfig:(BOOL)isOnlineConfig;



@end
