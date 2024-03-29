using System;
using System.Collections.Generic;
using System.ComponentModel;
using System.Data;
using System.Drawing;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
using System.Windows.Forms;

namespace gsbRapports
{
    public partial class rechercheMed : Form
    {
        private gsbrapportsEntities entities;
        public rechercheMed(gsbrapportsEntities entities)
        {
            InitializeComponent();
            this.entities = entities;

        }

       
    }
}
