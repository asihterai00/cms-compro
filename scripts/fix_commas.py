import glob

for f in glob.glob(r'C:\Devaron\www\cms-compro\languages\*.php'):
    with open(f, 'r', encoding='utf-8') as fp:
        c = fp.read()
    c = c.replace("'footer_email' => 'corporate@n-plast.co.id'\n", "'footer_email' => 'corporate@n-plast.co.id',\n")
    c = c.replace("'footer_email' => 'corporate@n-plast.co.id'\r\n", "'footer_email' => 'corporate@n-plast.co.id',\r\n")
    with open(f, 'w', encoding='utf-8') as fp:
        fp.write(c)

print('Fixed commas!')
