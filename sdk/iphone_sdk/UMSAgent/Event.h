//
//  Event.h
//  UMSAgent
//
//  Created by  on 12-3-21.
//  Copyright (c) 2012年 __MyCompanyName__. All rights reserved.
//

#import <Foundation/Foundation.h>

@interface Event : NSObject<NSCoding>
{
    NSString *event_id;
    NSString *time;
    NSString *activity;
    NSString *label;
    int acc;
    NSString *version;
}

@property (nonatomic,strong) NSString *event_id;
@property (nonatomic,strong) NSString *time;
@property (nonatomic,strong) NSString *activity;
@property (nonatomic,strong) NSString *label;
@property (nonatomic) int acc;
@property (nonatomic,strong) NSString *version;

@end
