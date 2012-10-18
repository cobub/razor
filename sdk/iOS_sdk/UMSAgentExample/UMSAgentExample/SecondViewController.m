//
//  SecondViewController.m
//  UMSAgentExample
//
//  Created by guowei on 12-10-14.
//
//

#import "SecondViewController.h"
#import "UMSAgent.h"

@interface SecondViewController ()

@end

@implementation SecondViewController

- (id)initWithNibName:(NSString *)nibNameOrNil bundle:(NSBundle *)nibBundleOrNil
{
    self = [super initWithNibName:nibNameOrNil bundle:nibBundleOrNil];
    if (self) {
        // Custom initialization
    }
    return self;
}

-(void)viewWillAppear:(BOOL)animated
{
    [UMSAgent startTracPage:@"SecondPage"];
}

-(void)viewWillDisappear:(BOOL)animated
{
    [UMSAgent endTracPage:@"SecondPage"];
}

- (void)viewDidLoad
{
    [super viewDidLoad];
    // Do any additional setup after loading the view from its nib.
}

- (void)didReceiveMemoryWarning
{
    [super didReceiveMemoryWarning];
    // Dispose of any resources that can be recreated.
}

-(IBAction)back:(id)sender
{
    [self dismissModalViewControllerAnimated:YES];
}

@end
