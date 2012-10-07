//
//  CommonReturn.h
//  UMSAgent
//
//  Created by  on 12-3-19.
//  Copyright (c) 2012年 __MyCompanyName__. All rights reserved.
//

#import <Foundation/Foundation.h>

@interface CommonReturn : NSObject
{
    int flag;
    NSString *msg;
}
@property(nonatomic) int flag;
@property(nonatomic,strong)NSString *msg;

@end
