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
	NSString *version;
	NSString *userid;
}
@property (nonatomic,strong) NSString *tags;
@property (nonatomic,strong) NSString *deviceid;
@property (nonatomic,strong) NSString *productkey;
@property (nonatomic,strong) NSString *version;
@property (nonatomic,strong) NSString *userid;
@end
