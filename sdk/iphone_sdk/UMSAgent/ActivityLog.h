//
//  ActivityLog.h
//  UMSAgent
//
//  Created by  on 12-4-5.
//  Copyright (c) 2012年 __MyCompanyName__. All rights reserved.
//

#import <Foundation/Foundation.h>

@interface ActivityLog : NSObject<NSCoding>
{
    NSString *sessionMils;
    NSString *startMils;
    NSString *endMils;
    NSString *duration;
    NSString *activity;
    NSString *version;
}

@property (nonatomic,strong) NSString *sessionMils;
@property (nonatomic,strong) NSString *startMils;
@property (nonatomic,strong) NSString *endMils;
@property (nonatomic,strong) NSString *duration;
@property (nonatomic,strong) NSString *activity;
@property (nonatomic,strong) NSString *version;

@end
