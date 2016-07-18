//
//  UMSAgent_Tests.m
//  UMSAgent Tests
//
//  Created by tim on 14/12/5.
//
//

#import <UIKit/UIKit.h>
#import <XCTest/XCTest.h>
#import "UMSAgent.h"

@interface UMSAgent_Tests : XCTestCase

@end

@implementation UMSAgent_Tests

- (void)setUp {
    [super setUp];
    [UMSAgent setGPSLocation:32.09888888 longitude:118.3909000];
    [UMSAgent startWithAppKey:@"a74b3ac054fa11e48f3600163e0240bd" ReportPolicy:REALTIME serverURL:@"http://115.29.208.35:8008"];
    [UMSAgent setIsLogEnabled:YES];
}

- (void)tearDown {
    // Put teardown code here. This method is called after the invocation of each test method in the class.
    [super tearDown];
}

- (void)testAPPKeySettings {
    XCTAssert(YES, @"Pass");
}

- (void)testPerformanceExample {
    // This is an example of a performance test case.
    [self measureBlock:^{
       
    }];
}

@end
