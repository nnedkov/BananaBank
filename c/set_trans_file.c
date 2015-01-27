#include <stdio.h>
#include <string.h>
#define MAX 500


char dest[15][12],amount[15][30], description[15][100];
int i = 0;

void tokenize(char order[])
{    
    char *ptr1;
    ptr1 = strtok(order,",");

    if(ptr1 != NULL)
    {
	strcpy(dest[i],ptr1);
        ptr1 = strtok(NULL,",");
	if(ptr1 != NULL)
		strcpy(amount[i],ptr1);
	ptr1 = strtok(NULL,",");
	if(ptr1 != NULL)
		strcpy(description[i],ptr1);
    }
	i++;
}

int read_file(char *filename)
{
    char order[MAX];
    FILE * fptr= NULL;
    if (( fptr = fopen(filename, "r")) == NULL)
    {
        return -1;
    }
    else
    {
        
	while (fgets(order, MAX, fptr)) {
    		tokenize(order);	
	}
	if (ferror(fptr)) {
    	return -1;
	}
}
return 0;
}


int clear_lines(char *filename){
	FILE *fp1, *fp2;
        char str[MAX];
	
        /* open the input file in read mode */
        fp1 = fopen(filename, "r");

        /* incase if file pointer is null */
        if (!fp1) {
                printf("Unable to open input file!!\n");
                return 0;
        }

        /* open another file in write mode */
        fp2 = fopen("/var/www/banana_bank/.bank_uploads/temp.txt", "w");

        if (!fp2) {
                printf("Unable to open the file to write\n");
                return 0;
        }

        /* copy the contents of file 1 to file 2 except all blank lines */
        while (!feof(fp1)) {
                fgets(str, MAX, fp1);
                if (strcmp(str, "\n") == 0 || strcmp(str, "\r\n") == 0) {
                        continue;
                }
                fputs(str, fp2);
                strcpy(str, "\0");
        }
 	/* close both the files */
        fclose(fp1);
        fclose(fp2);
 
        /* remove in the source file(with blank lines) */
        remove(filename);
        /* rename output file to source file name */
        rename("/var/www/banana_bank/.bank_uploads/temp.txt", filename);
        return 1;
  }

int main(int argc, char *argv[])
{
	int l;
	if(clear_lines(argv[1])){
		read_file(argv[1]);
	}
	
	for(l = 0; l < i; l++)
		printf("%s %s %s",dest[l],amount[l],description[l]);
	return 0;
}
