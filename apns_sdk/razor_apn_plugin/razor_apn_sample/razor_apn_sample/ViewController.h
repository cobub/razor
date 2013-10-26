//
//  ViewController.h
//  razor_apn_sample
//
//  Created by guowei on 13-9-4.
//  Copyright (c) 2013年 WBTECH. All rights reserved.
//

#import <UIKit/UIKit.h>

@interface ViewController : UIViewController
{
    UILabel *labelDeviceId;
    UILabel *labelDeviceToken;
}

@property (nonatomic,retain) IBOutlet UILabel *labelDeviceId;
@property (nonatomic,retain) IBOutlet UILabel *labelDeviceToken;

@end
