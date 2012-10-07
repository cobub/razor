//
//  ErrorLog.h
//  UMSAgent
//
//  Created by  on 12-5-16.
//  Copyright (c) 2012年 __MyCompanyName__. All rights reserved.
//

#import <Foundation/Foundation.h>

@interface ErrorLog : NSObject<NSCoding>
{
    NSString *stackTrace;
    NSString *time;
    NSString *activity;
    NSString *appkey;
    NSString *osVersion;
    NSString *deviceID;
    NSString *version;
}

@property (nonatomic,strong) NSString *stackTrace;
@property (nonatomic,strong) NSString *time;
@property (nonatomic,strong) NSString *activity;
@property (nonatomic,strong) NSString *appkey;
@property (nonatomic,strong) NSString *osVersion;
@property (nonatomic,strong) NSString *deviceID;
@property (nonatomic,strong) NSString *version;


@end
