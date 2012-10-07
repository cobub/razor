//
//  ConfigPreference.h
//  UMSAgent
//
//  Created by  on 12-3-23.
//  Copyright (c) 2012年 __MyCompanyName__. All rights reserved.
//

#import <Foundation/Foundation.h>
#include "CommonReturn.h"

@interface ConfigPreference : CommonReturn
{
    NSString *autogetlocation;
    NSString *Updateonlywifi;
    NSString *sessionmillis;
    NSString *reportpolicy;
}
@property(strong,nonatomic) NSString *autogetlocation;
@property(strong,nonatomic) NSString *Updateonlywifi;
@property(strong,nonatomic) NSString *sessionmillis;
@property(strong,nonatomic) NSString *reportpolicy;
@end
