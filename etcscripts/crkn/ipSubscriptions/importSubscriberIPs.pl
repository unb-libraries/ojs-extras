#!/usr/bin/perl -w

use strict;
use DBI;
use Socket;
use XML::LibXML;
use LWP::UserAgent;

sub getIP($);

my $url = 'http://www.erudit.org/ws/crkn/ipunb.xml';
my $ua = LWP::UserAgent->new;
$ua->timeout(300);
$ua->env_proxy;

my $response = $ua->get($url);
if ($response->is_success && $response->content ne '') {

    my $content = $response->content;
    my $dbh = DBI->connect('dbi:mysql:database=ojs2;host=localhost;port=3306', "username", "password", { RaiseError => 1, AutoCommit => 0 });
    $dbh->do("TRUNCATE TABLE crkn_ips");
    my $doc = XML::LibXML->new->parse_string($content);
    my $nodelist = $doc->findnodes('//listeip');
 
    foreach my $node ($nodelist->get_nodelist) {

        my @children = $node->childNodes;
        my ($instnode, $ipnode);
        foreach my $child (@children) {

            if ($child->nodeName eq 'abonne') { $instnode = $child; }
            if ($child->nodeName eq 'ip')     { $ipnode = $child; }
        }

        if (ref($instnode) eq 'XML::LibXML::Element' && ref($ipnode) eq 'XML::LibXML::Element') {

            my $inst = $instnode->textContent;
            my $ip   = $ipnode->textContent;

            if ($ip =~ /[a-z]/i) { # it's a domain name, not an IP address
                $ip = getIP($ip);
            }

	    $ip =~ s/\*/0/g;

	    my @octets = split(/\./, $ip);
	    my $start = 0;
	    foreach my $octet (@octets) {
		$start <<= 8;
		$start |= $octet;
	    }

            # convert to the top of the ranges now
	    $ip =~ s/\.0\./.255./;
	    $ip =~ s/\.0$/.255/;

	    @octets = split(/\./, $ip);
	    my $end = 0;
	    foreach my $octet (@octets) {
		$end <<= 8;
		$end |= $octet;
	    }

	    $ip   = $dbh->quote($ip);
            $inst = $dbh->quote($inst);

	    my $query = "REPLACE INTO crkn_ips (ip, start, end, institution) VALUES ($ip, $start, $end, $inst)";

	    my $sth = $dbh->prepare($query);
	    $sth->execute();

        }
    }

    $dbh->disconnect;
}

exit;

sub getIP($) {

   my $name = shift;
   my $packed_ip = gethostbyname($name);
   my $ip_address = "";

   if (defined $packed_ip) {
       $ip_address = inet_ntoa($packed_ip);
   }
   return $ip_address;

}
