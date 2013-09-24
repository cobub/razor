//
//  AppDelegate.m
//  razor_apn_sample
//
//  Created by guowei on 13-9-4.
//  Copyright (c) 2013年 WBTECH. All rights reserved.
//

#import "AppDelegate.h"

#import "ViewController.h"
#import "razor_apn_plugin.h"
#import <AdSupport/AdSupport.h>
#import "UMSAgent.h"

@implementation AppDelegate

- (void)dealloc
{

}

- (BOOL)application:(UIApplication *)application didFinishLaunchingWithOptions:(NSDictionary *)launchOptions
{
    self.window = [[UIWindow alloc] initWithFrame:[[UIScreen mainScreen] bounds]];
    // Override point for customization after application launch.
    self.viewController = [[ViewController alloc] initWithNibName:@"ViewController" bundle:nil];
    self.window.rootViewController = self.viewController;
    [self.window makeKeyAndVisible];
    [[UIApplication sharedApplication] registerForRemoteNotificationTypes:
     (UIRemoteNotificationTypeBadge | UIRemoteNotificationTypeSound | UIRemoteNotificationTypeAlert)];
    [UMSAgent startWithAppKey:@"2f5aa53b5ae03307274b596bbbeaa9ff" ReportPolicy:REALTIME ServerURL:@"http://192.168.1.104:8877/rcobub/index.php?"];
    return YES;
}

- (void)application:(UIApplication*)application didRegisterForRemoteNotificationsWithDeviceToken:(NSData*)deviceToken
{
    NSString *tokenKey = [[NSString alloc] initWithFormat:@"%@",deviceToken];
    tokenKey = [tokenKey stringByReplacingOccurrencesOfString:@"<" withString:@""];
    tokenKey = [tokenKey stringByReplacingOccurrencesOfString:@">" withString:@""];
    tokenKey = [tokenKey stringByReplacingOccurrencesOfString:@" " withString:@""];
    deviceToken = [NSString stringWithFormat:@"%@",deviceToken];
    
    //Get Device ID
//    NSString *adId = [[[ASIdentifierManager sharedManager] advertisingIdentifier] UUIDString];
    NSString *adId = [UMSAgent getUMSUDID];
    [razor_apn_plugin resisterDevice:adId token:tokenKey appId:@"60c55f3ea1a5c288edecdbe06cdff9e5"];
    
    NSLog(@"My token is: %@", tokenKey);
}

- (void)applicationWillResignActive:(UIApplication *)application
{
    // Sent when the application is about to move from active to inactive state. This can occur for certain types of temporary interruptions (such as an incoming phone call or SMS message) or when the user quits the application and it begins the transition to the background state.
    // Use this method to pause ongoing tasks, disable timers, and throttle down OpenGL ES frame rates. Games should use this method to pause the game.
}

- (void)applicationDidEnterBackground:(UIApplication *)application
{
    // Use this method to release shared resources, save user data, invalidate timers, and store enough application state information to restore your application to its current state in case it is terminated later. 
    // If your application supports background execution, this method is called instead of applicationWillTerminate: when the user quits.
}

- (void)applicationWillEnterForeground:(UIApplication *)application
{
    // Called as part of the transition from the background to the inactive state; here you can undo many of the changes made on entering the background.
}

- (void)applicationDidBecomeActive:(UIApplication *)application
{
    // Restart any tasks that were paused (or not yet started) while the application was inactive. If the application was previously in the background, optionally refresh the user interface.
}

- (void)applicationWillTerminate:(UIApplication *)application
{
    // Called when the application is about to terminate. Save data if appropriate. See also applicationDidEnterBackground:.
}

@end
