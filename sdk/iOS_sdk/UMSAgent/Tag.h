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
    NSString *tag;
    NSString *deviceid;
    NSString *productkey;
    NSString *lib_version;
    NSString *useridentifier;
}
@property (nonatomic,strong) NSString *tag;
@property (nonatomic,strong) NSString *deviceid;
@property (nonatomic,strong) NSString *productkey;
@property (nonatomic,strong) NSString *lib_version;
@property (nonatomic,strong) NSString *useridentifier;

@end
