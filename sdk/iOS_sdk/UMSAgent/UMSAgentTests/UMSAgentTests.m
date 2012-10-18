//
//  UMSAgentTests.m
//  UMSAgentTests
//
//  Created by  on 12-3-16.
//  Copyright (c) 2012年 __MyCompanyName__. All rights reserved.
//

#import "UMSAgentTests.h"


@implementation UMSAgentTests

- (void)setUp
{
    [super setUp];
    
    // Set-up code here.
}

- (void)tearDown
{
    // Tear-down code here.
    
    [super tearDown];
}

- (void)testPostClientData
{
    [[UMSAgent getInstance] postClientDataInBackground];
}

@end
