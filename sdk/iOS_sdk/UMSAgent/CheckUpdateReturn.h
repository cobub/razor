//
//  CheckUpdateReturn.h
//  UMSAgent
//
//  Created by  on 12-3-20.
//  Copyright (c) 2012年 __MyCompanyName__. All rights reserved.
//

#import <Foundation/Foundation.h>
#import "CommonReturn.h"
@interface CheckUpdateReturn : CommonReturn
{
    NSString *description;
    NSString *time;
    NSString *fileurl;
    NSString *forceUpdate;
    NSString *version;
    
}
@property(nonatomic,strong) NSString *description;
@property(nonatomic,strong) NSString *time;
@property(nonatomic,strong) NSString *fileurl;
@property(nonatomic,strong) NSString *forceUpdate;
@property(nonatomic,strong) NSString *version;

@end
