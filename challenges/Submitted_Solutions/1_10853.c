#include<stdio.h>
#include<math.h>
#include<string.h>
/*

Top Coder(Round2): Scary Products-Solution

Author: Ankur Kesharwani
		B.Tech(Information Technology) 2nd Year

			
Compiled by gcc(GNU C Compiler) and tested under
Fedora-15(a Linux distribution) Platform.
	
	
The following program takes two "long int" type 
numbers and prints the product of them. It uses
anvarray to store the product and multiblication
is performed digit wise.

*/


//using namespace std;	//No need to include this line in TurboC++ and DevC++.

void display(int product[100],int n)
{
	while(n>=0)
	{
		printf("%d",product[n]);
		n--;	
	}
}
int multiply(int product[100],int num1,int num2)
{
	int temp1=num1;
	int temp2=num2;
	int digit1,digit2,digit,carry,index,start,temp,end;
	int i,t1,t2;
	carry=0;
	index=0;
	start=0;
	
	for(i=0;i<100;i++)
		product[i]=0;
	
	while(temp2>0)
	{
		digit2=temp2%10;
		temp2=temp2/10;
		while(temp1>0)
		{
			digit1=temp1%10;
			temp1=temp1/10;
			
			digit=(digit1*digit2)+product[index]+carry;
			t1=digit%10;
			carry=digit/10;
			product[index]=t1;
			index++;
		}

		if(carry>0)
		{
			product[index]=carry;
			carry=0;
		}
		start++;
		end=index;
		index=start;
		temp1=num1;
		
	}
	return end;
}

int main()
{
	int num1;
	int num2;
	int product[1000];
	int index_of_last_digit; 
	scanf("%d",&num1);
	scanf("%d",&num2);
	index_of_last_digit=(multiply(product,num1,num2))-1;
	display(product,index_of_last_digit);
	return 0;
}
