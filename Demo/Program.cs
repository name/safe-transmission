using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
using System.Collections.Specialized;

using SafeTransmission;

namespace Demo {
    class Program {
        static void Main(string[] args) {

            /* Setup SafeTransmission */
            SafeTransmission.SafeTransmission safeTransmission = new SafeTransmission.SafeTransmission("EncryptionKey");
            safeTransmission.NullifyProxy = true;
            NameValueCollection test = new NameValueCollection();

            /* Set POST Data & Request */
            test["key"] = "value";
            Response response = safeTransmission.Request("https://maddex.co/demo.php", test);

            /* Display Reponse Data */
            Console.WriteLine($"Raw: {response.raw}");
            Console.WriteLine($"Status: {response.status}");
            Console.WriteLine($"Message: {response.message}");
            Console.WriteLine($"Extra: {response.GetData<string>("charlie")}");
            Console.WriteLine($"POST Data: {response.GetData<string>("key")}");

            Console.ReadLine();
        }
    }
}