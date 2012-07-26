//
//  Deviceinfo.h
//  UMSAgent
//
//  Created by  on 12-3-20.
//  Copyright (c) 2012年 __MyCompanyName__. All rights reserved.
//

#import <Foundation/Foundation.h>

@interface Deviceinfo : NSObject
{
    NSString *platform;
    NSString *os_version;
    NSString *language;
    NSString *resolution;
    NSString *deviceID;
    Boolean isMobileDevice;
    NSString *MCCMNC;
    NSString *network;
    NSString *version;
    NSString *devicename;
    NSString *modulename;
    NSString *time;
    NSString *isJailbroken;
}

@property(nonatomic,strong) NSString *platform;
@property(nonatomic,strong) NSString *os_version;
@property(nonatomic,strong) NSString *language;
@property(nonatomic,strong) NSString *resolution;
@property(nonatomic,strong) NSString *deviceID;
@property(nonatomic) Boolean isMobileDevice;
@property(nonatomic,strong) NSString *MCCMNC;
@property(nonatomic,strong) NSString *network;
@property(nonatomic,strong) NSString *version;
@property(nonatomic,strong) NSString *devicename;
@property(nonatomic,strong) NSString *modulename;
@property(nonatomic,strong) NSString *time;
@property(nonatomic,strong) NSString *isJailbroken;

@end
