using System;
using System.Collections.Generic;
using System.ComponentModel;
using System.Data;
using System.Drawing;
using System.Linq;
using System.Text;
using System.Windows.Forms;

namespace gsbRapports
{
    public partial class Form1 : Form
    {
        private gsbrapports2021Entities entities;
        public Form1()
        {
            InitializeComponent();
            this.entities = new gsbrapports2021Entities();
        }

       

        private void listeToolStripMenuItem_Click(object sender, EventArgs e)
        {
            MedListe rm = new MedListe(this.entities);
            rm.MdiParent = this;
            rm.Show();
        }
    }
}
