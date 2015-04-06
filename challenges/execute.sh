#!/bin/sh
ulimit -v 10240
ulimit -m 10240
ulimit -t 1
./Compiled_Solutions/$1.out<./Sample_Inputs/$2.input1>./Temp/$1.output1
./Compiled_Solutions/$1.out<./Sample_Inputs/$2.input2>./Temp/$1.output2
./Compiled_Solutions/$1.out<./Sample_Inputs/$2.input3>./Temp/$1.output3
exit 1;
