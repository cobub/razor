//
//  UsingLogDao.h
//  UMSAgentExample
//
//  Created by tim on 14/12/11.
//
//

#import <Foundation/Foundation.h>
#import "ActivityLog.h"

@interface UsingLogDao : NSObject

+ (NSMutableArray *)getArchiveActivityLog;

+ (void)postActivityArray:(NSMutableArray*)activityArray appKey:(NSString*)appKey;

+ (void)postActivity:(ActivityLog *)mLog appKey:(NSString *)appkey;

@end
