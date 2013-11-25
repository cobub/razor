//
//  PostTagDao.h
//  UMSAgent
//
//  Created by admin on 13-4-27.
//
//

#import <Foundation/Foundation.h>
#import "Tag.h"
#import "CommonReturn.h"
@interface PostTagDao : NSObject
+(CommonReturn *) postTag:(NSString *) appkey tag:(Tag *) tag;
@end
