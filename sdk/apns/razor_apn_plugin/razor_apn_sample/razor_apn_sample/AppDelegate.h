//
//  AppDelegate.h
//  razor_apn_sample
//
//  Created by guowei on 13-9-4.
//  Copyright (c) 2013年 WBTECH. All rights reserved.
//

#import <UIKit/UIKit.h>

@class ViewController;

@interface AppDelegate : UIResponder <UIApplicationDelegate>
{
    NSString *deviceId;
    NSString *deviceToken;
}

@property (strong, nonatomic) UIWindow *window;

@property (strong, nonatomic) ViewController *viewController;

@end
