//
//  LoginViewController.h
//  UMSAgentExample
//
//  Created by tim on 1/14/15.
//
//

#import <UIKit/UIKit.h>

@interface LoginViewController : UIViewController

@property (nonatomic,retain) IBOutlet UITextField *textUserName;

- (IBAction)onLoginButtonClicked:(id)sender;
@end
