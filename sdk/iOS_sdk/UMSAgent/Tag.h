//
//  Tag.h
//  UMSAgent
//
//  Created by admin on 13-4-27.
//
//

#import <Foundation/Foundation.h>

@interface Tag : NSObject<NSCoding>
{
    NSString *tags;
    NSString *deviceid;
    NSString *productkey;
}
@property (nonatomic,strong) NSString *tags;
@property (nonatomic,strong) NSString *deviceid;
@property (nonatomic,strong) NSString *productkey;
@end
