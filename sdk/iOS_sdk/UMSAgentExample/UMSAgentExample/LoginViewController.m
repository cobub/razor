//
//  LoginViewController.m
//  UMSAgentExample
//
//  Created by tim on 1/14/15.
//
//

#import "LoginViewController.h"
#import "ViewController.h"

@interface LoginViewController ()

@end

@implementation LoginViewController
@synthesize textUserName;

- (void)viewDidLoad {
    [super viewDidLoad];
    [self loadUserName];
}

- (void)didReceiveMemoryWarning {
    [super didReceiveMemoryWarning];
    // Dispose of any resources that can be recreated.
}

- (IBAction)onLoginButtonClicked:(id)sender
{
    if([textUserName.text isEqualToString:@""])
    {
        UIAlertView *alertView = [[UIAlertView alloc] initWithTitle:@"Alert" message:@"Username can not be null" delegate:self cancelButtonTitle:@"ok" otherButtonTitles:nil, nil];
        [alertView show];
        return;
    }
    else
    {
        [[NSUserDefaults standardUserDefaults] setObject:textUserName.text forKey:@"username"];
        [UMSAgent bindUserIdentifier:textUserName.text];
        [UMSAgent postEvent:@"e_sys_login"];
    }
    ViewController * viewController = [[ViewController alloc] init];

    [self presentViewController:viewController animated:YES completion:nil];
}

-(void)loadUserName
{
    NSString *userName = [[NSUserDefaults standardUserDefaults] objectForKey:@"username"];
    if(userName)
    {
        [textUserName setText:userName];
    }
}

/*
#pragma mark - Navigation

// In a storyboard-based application, you will often want to do a little preparation before navigation
- (void)prepareForSegue:(UIStoryboardSegue *)segue sender:(id)sender {
    // Get the new view controller using [segue destinationViewController].
    // Pass the selected object to the new view controller.
}
*/

@end
