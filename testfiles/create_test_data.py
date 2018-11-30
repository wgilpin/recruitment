# for the sample data, generate a test data file
#
# usage:
#   python3 create_test_data.py scope
# where scope is a key to the url dict below (eg 'contract')
#
# output:
#  a file in the current dir with name '{scope}.data'
#  eg 'contract.data'

import re
import sys
from urllib.request import urlopen

# url mapping
urls = {
  'contract': 'https://ascee.droeftoeters.com/backend/Esi/Documentation/contract.php',
}

usage = """

For the sample data, generate a test data file

usage:
  python create_test_data.py scope
where scope is a key to the url dict below (eg 'contract')

output:
  a file in the current dir with name '{scope}.data'
  eg 'contract.data'
"""

if len(sys.argv) < 2 or len(sys.argv) > 3 :
  print(f"Error: wrong arguments {len(sys.argv)}")
  print(usage)
  sys.exit(1)

scope = sys.argv[1]
print('Analysing scope '+scope)

if not scope in urls:
  print(f"ERROR: Scope {scope} not found")
  print(usage)
  sys.exit(2)

out = urlopen(urls[scope]).read().decode('utf-8')

if len(sys.argv) == 2 or sys.argv[2] != 'raw':

  r_sample = re.compile(r"^(.+)\n((?:\n.+)+)", re.MULTILINE)

  out = re.sub(r" => Array", ": ", out)
  # add quotes to multiline strings
  r1 = re.compile(r"\[description\] => ([\w\s\d,.<>:\(\)=\/'\"#\-]*)(\)$)", re.MULTILINE)
  out = re.sub(r1, r"\[description\] => \"\g<1>\"\g<2>", out)

  out = re.sub(r" => ", ": ", out)
  # remove square brackets around dict keys
  out = re.sub(r"\[([\w_]*)\]:", r"\g<1>:", out)


with open(f"{scope}.data", "w") as out_file:
  out_file.writelines(out)

print('DONE')
sys.exit(0)